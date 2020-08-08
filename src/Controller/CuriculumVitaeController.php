<?php

namespace App\Controller;

use App\Entity\Main\CV\CompetenceCategorie;
use App\Entity\Main\CV\Technologie;
use App\Utils\Assets\AssetsResponse;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CuriculumVitaeController extends AbstractController
{
    public const CACHE_KEY_TECHNOLOGIE = 'cv_technologies';
    public const TECHNOLOGIES_SIZE_WIDTH = 110;

    private AdapterInterface $cache;

    public function __construct(AdapterInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @Route("/cv/my-skills", name="cv-my_skills")
     *
     * @throws InvalidArgumentException
     */
    public function my_skills(): Response
    {
        $i = $this->cache->getItem(self::CACHE_KEY_TECHNOLOGIE);
        if (!$i->isHit() || true) {
            $i->set($this->getDoctrine()->getRepository(CompetenceCategorie::class)->findBy([], ['ordre' => 'asc']));
            $this->cache->save($i);
        }
        /** @var CompetenceCategorie[] $skills */
        $skills = $i->get();

        return $this->render('index/cv/my_skills.twig', [
            'skills' => $skills,
        ]);
    }

    /**
     * @Route("/cv/my-skills/icon/{slug}", name="cv-technology_icon")
     *
     * @throws InvalidArgumentException
     */
    public function technology_icon(Technologie $tech): Response
    {
        return AssetsResponse::ReturnImgAdapterCache($this->cache, $tech->getImage(), $tech->getSlug(), $tech->getImageExtension(), self::TECHNOLOGIES_SIZE_WIDTH);
    }
}
