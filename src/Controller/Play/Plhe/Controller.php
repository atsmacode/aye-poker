<?php

namespace App\Controller\Play\Plhe;

use App\Service\PokerGame;
use Atsmacode\PokerGame\Controllers\PotLimitHoldEm\HandController as PlheHandController;
use Atsmacode\PokerGame\Controllers\PotLimitHoldEm\PlayerActionController as PlhePlayerActionController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Controller extends AbstractController
{
    #[Route('/play/plhe', name: 'play_plhe', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('play/index.html.twig');
    }

    #[Route('/play/plhe', name: 'play_plhe_start', methods: ['POST'])]
    public function start(PokerGame $pokerGame): Response
    {
        $serviceManager = $pokerGame->getServiceManager();
        $response       = $serviceManager->get(PlheHandController::class)->play()->getContent();

        return new Response($response);
    }

    #[Route('/action/plhe', name: 'action_plhe', methods: ['POST'])]
    public function action(Request $request, PokerGame $pokerGame): Response
    {
        $serviceManager = $pokerGame->getServiceManager();
        $response       = $serviceManager->get(PlhePlayerActionController::class)->action($request)->getContent();

        return new Response($response);
    }
}
