<?php

namespace App\Controller\SilvainEu;

use App\Entity\Main\SilvainEu\Service;
use App\Utils\Assets\AssetsResponse;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends AbstractController
{
    public const SERVICE_SIZE_WIDTH = 200;
    public const CACHE_KEY_SERVICES = 'cv_services';

    /**
     * @var AdapterInterface
     */
    private AdapterInterface $cache;

    public function __construct(AdapterInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @Route("/silvaineu/", name="silvaineu_service_index")
     *
     * @return Response
     */
    public function Hub()
    {
        $i = $this->cache->getItem(self::CACHE_KEY_SERVICES);
        if (!$i->isHit()) {
            $i->set($this->getDoctrine()->getRepository(Service::class)->findAll());
            $this->cache->save($i);
        }

        /** @var Service[] $services */
        $services = $i->get();

        return $this->render('index/silvain.eu/index.html.twig', [
            'services' => $services,
        ]);
    }

    /**
     * @Route("/silvaineu/service/{slug}/icon", name="silvaineu_service_icon")
     *
     * @throws InvalidArgumentException
     *
     * @return Response
     */
    public function service_icon(Service $s): Response
    {
        return AssetsResponse::ReturnImgAdapterCache($this->cache, $s->getImage(), $s->getSlug(), $s->getImageExtension(), self::SERVICE_SIZE_WIDTH);
    }
}
