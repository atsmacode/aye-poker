<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlayController extends AbstractController
{
    #[Route('/play', name: 'app_play')]
    public function index(): Response
    {
        return $this->render('play/index.html.twig', [
            'controller_name' => 'PlayController',
        ]);
    }
}
