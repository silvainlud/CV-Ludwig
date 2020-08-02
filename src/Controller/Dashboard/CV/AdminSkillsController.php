<?php

namespace App\Controller\Dashboard\CV;

use App\Entity\Main\CV\CompetenceCategorie;
use App\Form\CV\CompetenceCategorieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminSkillsController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/dashboard/cv/skills", name="db_vc_skills")
     */
    public function View(Request $request): Response
    {
        $formCat = $this->createForm(CompetenceCategorieType::class);
        $categories = $this->em->getRepository(CompetenceCategorie::class)->findBy([], ['ordre' => 'asc']);

        return $this->render('dashboard/cv/skills/index.html.twig', [
            'categories' => $categories,
            'formCat' => $formCat->createView(),
        ]);
    }
}
