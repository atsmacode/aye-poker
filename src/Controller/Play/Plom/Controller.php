<?php

namespace App\Controller\Play\Plom;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Controller extends AbstractController
{
    #[Route('/play/plom', name: 'play_plom')]
    public function index(): Response
    {
        return $this->render('play/index.html.twig');
    }
}
