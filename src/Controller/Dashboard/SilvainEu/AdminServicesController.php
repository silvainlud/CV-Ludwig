<?php

namespace App\Controller\Dashboard\SilvainEu;

use App\Controller\SilvainEu\ServiceController;
use App\Entity\Main\SilvainEu\Service;
use App\Form\SilvainEu\ServiceType;
use App\Utils\Assets\AssetsResponse;
use App\Utils\StringHelper;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminServicesController.
 *
 * @IsGranted("ROLE_ADMIN_SILVAINEU")
 */
class AdminServicesController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/dashboard/silvaineu/services", name="db_silvaineu_services")
     */
    public function ListServices(): Response
    {
        /** @var Service[] $service */
        $service = $this->em->getRepository(Service::class)->findAll();

        return $this->render('dashboard/SilvainEu/service/index.html.twig', [
            'services' => $service,
        ]);
    }

    /**
     * @Route("/dashboard/silvaineu/services/add", name="db_silvaineu_services-add")
     */
    public function AddService(Request $request): Response
    {
        $s = new Service();
        $form = $this->createForm(ServiceType::class, $s, ['cancel_btn' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($s);
            $this->em->flush();

            return $this->redirectToRoute('db_silvaineu_services');
        }

        return $this->render('dashboard/SilvainEu/service/form.html.twig', [
            'form' => $form->createView(),
            'isEdit' => false,
        ]);
    }

    /**
     * @Route("/dashboard/silvaineu/services/edit/{service}", name="db_silvaineu_services-edit")
     * @ParamConverter("s", options={"mapping": {"service": "slug"}})
     */
    public function EditService(Request $request, Service $s): Response
    {
        $form = $this->createForm(ServiceType::class, $s, ['cancel_btn' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            return $this->redirectToRoute('db_silvaineu_services');
        }

        return $this->render('dashboard/SilvainEu/service/form.html.twig', [
            'form' => $form->createView(),
            'isEdit' => true,
        ]);
    }

    public static function RemoveServiceCache(EntityManagerInterface $em, AdapterInterface $cache): void
    {
        $keys = [StringHelper::strRemoveCacheKey(ServiceController::CACHE_KEY_SERVICES)];
        $_ts = $em->getRepository(Service::class)->findAll();
        foreach ($_ts as $t) {
            $keys[] = AssetsResponse::CacheKey($t->getImage(), $t->getSlug(), $t->getImageExtension(), ServiceController::SERVICE_SIZE_WIDTH);
        }
        $cache->deleteItems($keys);
    }
}
