<?php

namespace App\Controller\SilvainEu;

use App\Entity\Main\SilvainEu\Service;
use App\Utils\Assets\AssetsResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends AbstractController
{
    public const SERVICE_SIZE_WIDTH = 200;
    public const CACHE_KEY_SERVICES = 'cv_services';

    private AdapterInterface $cache;

    public function __construct(AdapterInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @Route("/silvaineu/", name="silvaineu_service_index")
     */
    public function Hub(): Response
    {
        return $this->render('index/silvain.eu/index.html.twig', [
            'services' => $this->getDoctrine()->getRepository(Service::class)->findAll(),
        ]);
    }

    /**
     * @Route("/silvaineu/service/{slug}/icon", name="silvaineu_service_icon")
     */
    public function service_icon(Service $s): Response
    {
        return AssetsResponse::ReturnImgAdapterCache($this->cache, $s->getImage(), $s->getSlug(), $s->getImageExtension(), self::SERVICE_SIZE_WIDTH);
    }

    /**
     * @Route("/silvaineu/email/config", name="silvaineu_service_config-email")
     */
    public function ConfigEmail(): Response
    {
        return $this->render('index/silvain.eu/mail/config.html.twig', [
        ]);
    }
}
