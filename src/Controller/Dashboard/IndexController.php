<?php

namespace App\Controller\Dashboard;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard_index")
     */
    public function Index(): Response
    {
        return $this->render('dashboard/base.html.twig');
    }
}
