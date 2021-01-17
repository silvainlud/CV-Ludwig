<?php

namespace App\Controller\Dashboard\CV;

use App\Controller\CuriculumVitaeController;
use App\Entity\Main\CV\Realisation;
use App\Entity\Main\CV\RealisationImage;
use App\Entity\Main\CV\RealisationImageGallerie;
use App\Entity\Main\CV\RealisationImageMiniature;
use App\Form\CV\Realisation\RealisationImageGallerieType;
use App\Form\CV\Realisation\RealisationType;
use App\Utils\Assets\AssetsResponse;
use App\Utils\StringHelper;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminMakingController extends AbstractController
{
    private EntityManagerInterface $em;

    private AdapterInterface $cache;

    public function __construct(EntityManagerInterface $em, AdapterInterface $cache)
    {
        $this->em = $em;
        $this->cache = $cache;
    }

    public static function RemoveMakingCache(EntityManagerInterface $em, AdapterInterface $cache): void
    {
        $keys = [StringHelper::strRemoveCacheKey(CuriculumVitaeController::CACHE_KEY_REALISATION)];
        /** @var RealisationImage[] $_ts */
        $_ts = $em->getRepository(RealisationImage::class)->findAll();
        foreach ($_ts as $t) {
            $keys[] = AssetsResponse::CacheKey($t->getImage(), (string) $t->getId(), null, null);
        }
        $_ts = $em->getRepository(Realisation::class)->findAll();
        foreach ($_ts as $t) {
            $keys[] = StringHelper::strRemoveCacheKey(CuriculumVitaeController::CACHE_KEY_REALISATION_VIEW . $t->getSlug());
            $keys[] = StringHelper::strRemoveCacheKey(CuriculumVitaeController::CACHE_KEY_REALISATION_VIEW_GALLERY . $t->getSlug());
        }

        $cache->deleteItems($keys);
    }

    /**
     * @Route("/dashboard/cv/making", name="db_cv_making")
     */
    public function ViewMaking(): Response
    {
        $_rs = $this->em->getRepository(Realisation::class)->findAll();

        return $this->render('dashboard/cv/making/viewMaking.html.twig', [
            'makings' => $_rs,
        ]);
    }

    /**
     * @Route("/dashboard/cv/making/add", name="db_cv_making_add")
     * @Route("/dashboard/cv/making/edit/{slug}", name="db_cv_making_edit")
     * @ParamConverter("_rea", options={"mapping": {"slug": "slug"}})
     */
    public function AddMaking(Request $request, Realisation $_rea = null): Response
    {
        $isAdd = true;
        $_reaGallery = null;
        $formGalleryRemove = null;
        $formGallery = null;
        if (null === $_rea) {
            $_rea = new Realisation();
        } else {
            $isAdd = false;
        }

        $form = $this->createForm(RealisationType::class, $_rea);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (null !== $_rea->getMainImage()) {
                if (null === $_rea->getMainImage()->getImageOrNull()) {
                    if (null !== $_rea->getMainImage()->getId()) {
                        $this->em->remove($_rea->getMainImage());
                    }
                    $_rea->setMainImage(null);
                } else {
                    $_rea->getMainImage()->setRealisation($_rea);
                }
            }

            $this->em->persist($_rea);

            /** @var RealisationImageMiniature $mainImg */
            $mainImg = $this->em->getRepository(RealisationImageMiniature::class)->findOneBy(['realisation' => $_rea->getId()]);
            if (null !== $mainImg && null !== $_rea->getMainImage() && $mainImg->getId() !== $_rea->getMainImage()->getId()) {
                $this->em->remove($mainImg);
            }

            $this->em->flush();

            return $this->redirectToRoute('db_cv_making');
        }

        if (false === $isAdd) {
            $_reaGallery = new RealisationImageGallerie();
            $_reaGallery->setRealisation($_rea);
            $formGallery = $this->createForm(RealisationImageGallerieType::class, $_reaGallery);
            $formGallery->handleRequest($request);
            if ($formGallery->isSubmitted() && $formGallery->isValid()) {
                $this->em->persist($_reaGallery);
                $_rea->addGallery($_reaGallery);
                $this->em->flush();

                return $this->redirectToRoute('db_cv_making_edit', ['slug' => $_rea->getSlug()]);
            }

            $formGalleryRemove = [];
            /** @var FormFactoryInterface $formFactory */
            $formFactory = $this->get('form.factory');
            foreach ($_rea->getGallery() as $ga) {
                $_tf = $formFactory->createNamedBuilder('remove-gallery-' . $ga->getid(), FormType::class)
                    ->add('submit', SubmitType::class, [
                        'label' => '<i class="fas fa-trash-alt"></i>',
                        'label_html' => true,
                        'attr' => ['class' => 'app-btn app-btn-red app-btn-text'],
                    ])->getForm();
                $_tf->handleRequest($request);
                if ($_tf->isSubmitted() && $_tf->isValid()) {
                    $_rea->removeGallery($ga);
                    $this->em->remove($ga);
                    $this->em->flush();

                    return $this->redirectToRoute('db_cv_making_edit', ['slug' => $_rea->getSlug()]);
                }
                $formGalleryRemove[$ga->getId()] = $_tf->createView();
            }
        }

        return $this->render('dashboard/cv/making/form_makink.html.twig', [
            'form' => $form->createView(),
            'formGallery' => null !== $formGallery ? $formGallery->createView() : null,
            'formGalleryRemove' => null !== $formGalleryRemove ? $formGalleryRemove : null,
            'gallery' => null !== $formGallery ? $_rea->getGallery() : null,
            'add' => $isAdd,
        ]);
    }
}
