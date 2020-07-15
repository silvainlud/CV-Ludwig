<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function Index()
    {
        return $this->render('index/base.html.twig');
    }
}
