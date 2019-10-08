<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ExoController extends AbstractController
{
    /**
     * @Route("/exo", name="exo")
     */
    public function index()
    {
        return $this->render('exo/login.html.twig');
    }
}
