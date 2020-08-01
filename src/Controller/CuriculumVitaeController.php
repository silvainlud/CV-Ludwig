<?php

namespace App\Controller;

use App\Entity\Main\CV\CompetenceCategorie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CuriculumVitaeController extends AbstractController
{
    /**
     * @Route("/cv/my-skills", name="cv-my_skills")
     *
     * @return Response
     */
    public function my_skills(): Response
    {
        /** @var CompetenceCategorie[] $skills */
        $skills = $this->getDoctrine()->getRepository(CompetenceCategorie::class)->findAll();

        return $this->render('index/cv/my_skills.twig', [
            'skills' => $skills,
        ]);
    }
}
