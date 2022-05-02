<?php

namespace App\Controller\Dashboard;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexDashboardController extends AbstractController
{
    #[Route(path: '/dashboard/', name: 'db_index')]
    public function Index(Request $request) : Response
    {
        return $this->render('dashboard/Mail/index.html.twig', [
        ]);
    }
}
