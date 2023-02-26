<?php

namespace App\Controller\Play\Plhe;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Controller extends AbstractController
{
    #[Route('/play/plhe', name: 'play_plhe', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('play/index.html.twig');
    }
}
