<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function Index(): Response
    {
        return $this->render('index/index.html.twig');
    }

    /**
     * @Route("/1", name="index2")
     */
    public function Index1(): Response
    {
        return $this->render('index/base.html.twig');
    }
}
