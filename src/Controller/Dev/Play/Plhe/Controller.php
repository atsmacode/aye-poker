<?php

namespace App\Controller\Dev\Play\Plhe;

use App\Service\PokerGame;
use Atsmacode\PokerGame\Controllers\Dev\PotLimitHoldEm\HandController as PlheHandController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Controller extends AbstractController
{
    #[Route('/dev/play/plhe', name: 'dev_play_plhe', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('play/index.html.twig');
    }

    #[Route('/dev/play/plhe', name: 'dev_play_plhe_start', methods: ['POST'])]
    public function start(PokerGame $pokerGame): Response
    {
        $serviceManager = $pokerGame->getServiceManager();
        $response       = $serviceManager->get(PlheHandController::class)->play()->getContent();

        return new Response($response);
    }
}
