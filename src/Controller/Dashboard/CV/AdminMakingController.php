<?php

namespace App\Controller\Dashboard\CV;

use App\Entity\Main\CV\Realisation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminMakingController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
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
}
