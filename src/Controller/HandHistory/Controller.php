<?php

namespace App\Controller\HandHistory;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Controller extends AbstractController
{
    #[Route('/handhistory', name: 'hand_history')]
    public function index(): Response
    {
        return $this->render('handhistory/index.html.twig');
    }
}
