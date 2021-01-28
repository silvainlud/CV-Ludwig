<?php

namespace App\Controller;

use App\Entity\Main\CV\CompetenceCategorie;
use App\Entity\Main\CV\Realisation;
use App\Entity\Main\CV\RealisationImage;
use App\Entity\Main\CV\RealisationImageGallerie;
use App\Entity\Main\CV\RealisationImageMiniature;
use App\Entity\Main\CV\Technologie;
use App\Repository\Main\CV\RealisationRepository;
use App\Utils\Assets\AssetsResponse;
use App\Utils\StringHelper;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CuriculumVitaeController extends AbstractController
{
    public const CACHE_KEY_TECHNOLOGIE = 'cv_technologies';
    public const CACHE_KEY_REALISATION = 'cv_making';
    public const CACHE_KEY_REALISATION_VIEW = 'cv_making_view_';
    public const CACHE_KEY_REALISATION_VIEW_LINKE_TECH = 'cv_making_view_link_tech_';
    public const CACHE_KEY_REALISATION_VIEW_GALLERY = 'cv_making_view_galley_';
    public const TECHNOLOGIES_SIZE_WIDTH = 110;

    private AdapterInterface $cache;

    private EntityManagerInterface $em;

    public function __construct(AdapterInterface $cache, EntityManagerInterface $em)
    {
        $this->cache = $cache;
        $this->em = $em;
    }

    /**
     * @Route("/competences", name="cv-my_skills")
     */
    public function my_skills(): Response
    {
        $i = $this->cache->getItem(self::CACHE_KEY_TECHNOLOGIE);
        if (!$i->isHit()) {
            $i->set($this->getDoctrine()->getRepository(CompetenceCategorie::class)->findAll());
            $this->cache->save($i);
        }

        /** @var CompetenceCategorie[] $skills */
        $skills = $i->get();

        return $this->render('index/cv/my_skills.twig', [
            'skills' => $skills,
        ]);
    }

    /**
     * @Route("/competences/logo/{slug}", name="cv-technology_icon")
     */
    public function technology_icon(Technologie $tech): Response
    {
        return AssetsResponse::ReturnImgAdapterCache($this->cache, $tech->getImage(), $tech->getSlug(), $tech->getImageExtension(), self::TECHNOLOGIES_SIZE_WIDTH);
    }

    /**
     * @Route("/cv", name="cv-my_career")
     */
    public function my_career(): Response
    {
        return $this->render('index/cv/my_cv.twig', []);
    }

    /**
     * @Route("/realisations", name="cv-making")
     */
    public function making(RealisationRepository $realisationRepository): Response
    {
        $i = $this->cache->getItem(self::CACHE_KEY_REALISATION);
        if (!$i->isHit()) {
            $i->set($realisationRepository->findAllWithImage());
            $this->cache->save($i);
        }

        /** @var Realisation[] $makings */
        $makings = $i->get();

        return $this->render('index/cv/making.twig', ['realisations' => $makings]);
    }

    /**
     * @Route("/cv/realisation/img/{rea}", name="cv-making-img")
     * @ParamConverter("_r", options={"mapping": {"rea": "id"}})
     */
    public function making_image(RealisationImage $_r): Response
    {
        if (!$this->isGranted('ROLE_ADMIN_CV')) {
            if ($_r instanceof RealisationImageMiniature || $_r instanceof RealisationImageGallerie) {
                if (!$_r->getRealisation()->isPublic()) {
                    throw $this->createNotFoundException();
                }
            }
        }

        return AssetsResponse::ReturnImgAdapterCache($this->cache, $_r->getImage(), (string) $_r->getId(), null, null);
    }

    /**
     * @Route("/cv/realisation/{slug}", name="cv-making-view")
     */
    public function making_view(string $slug, RealisationRepository $realisationRepository): Response
    {
        $i = $this->cache->getItem(StringHelper::strRemoveCacheKey(self::CACHE_KEY_REALISATION_VIEW . $slug));
        if (!$i->isHit()) {
            $i->set($realisationRepository->findBySlug($slug));
            $this->cache->save($i);
        }

        /** @var Realisation|null $v */
        $v = $i->get();
        if (null === $v || (!$v->isPublic() && !$this->isGranted('ROLE_PREVIEW_MAKING'))) {
            throw $this->createNotFoundException();
        }

        $alreadyDisplay = array_reduce($v->getTechnologies()->getValues(), function (array $acc, Technologie $t) {
            $acc[] = $t->getId();

            return $acc;
        }, []);

        $i2 = $this->cache->getItem(StringHelper::strRemoveCacheKey(self::CACHE_KEY_REALISATION_VIEW_LINKE_TECH . $slug));
        if (!$i2->isHit() || !$i->isHit()) {
            $i2->set(array_reduce($v->getTechnologies()->getValues(), function (array $acc, Technologie $t) use ($alreadyDisplay) {
                return array_merge($acc, array_reduce($t->getLinkedTechonologies()->getValues(), function (array $acc2, Technologie $lt) use ($alreadyDisplay, $acc) {
                    if (!\in_array($lt->getId(), $alreadyDisplay) && !\in_array($lt, $acc2) && !\in_array($lt, $acc)) {
                        $acc2[] = $lt;
                    }

                    return $acc2;
                }, []));
            }, []));
            $this->cache->save($i);
        }

        /** @var Technologie $linkTechno */
        $linkTechno = $i2->get();

        $i = $this->cache->getItem(StringHelper::strRemoveCacheKey(self::CACHE_KEY_REALISATION_VIEW_GALLERY . $slug));
        if (!$i->isHit()) {
            $i->set($this->em->getRepository(RealisationImageGallerie::class)->findBy(['realisations' => $v->getId()]));
            $this->cache->save($i);
        }

        /** @var RealisationImageGallerie[] $gallery */
        $gallery = $i->get();

        return $this->render('index/cv/making_view.twig', [
            'realisation' => $v,
            'gallery' => $gallery,
            'linkTechno' => $linkTechno,
        ]);
    }

    /**
     * @Route("/ui")
     */
    public function ui(): Response
    {
        return $this->render('index/ui.html.twig');
    }

    /**
     * @Route("/by")
     *
     * @return Response
     */
    public function by()
    {
        return new Response('Fait par Ludwig SILVAIN');
    }
}
