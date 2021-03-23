<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @return [type]
     *
     * @Route("/", name="homepage")
     */
    public function index()
    {
        return $this->render('default/index.html.twig');
    }
}
