<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * Renders the homepage.
     *
     * @Route("/", name="homepage")
     */
    public function homeAction()
    {
        return $this->render('home/index.html.twig');
    }
}
