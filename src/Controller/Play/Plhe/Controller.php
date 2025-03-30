<?php

namespace App\Controller\Play\Plhe;

use App\Service\MercureUpdate;
use App\Service\PokerGame;
use Atsmacode\PokerGame\Controllers\PotLimitHoldEm\SitController as PlheSitController;
use Atsmacode\PokerGame\Controllers\PotLimitHoldEm\PlayerActionController as PlhePlayerActionController;
use Atsmacode\PokerGame\Models\PlayerAction;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;

class Controller extends AbstractController
{
    const MERCURE_ACTION_TOPIC = 'player_action';

    public function __construct(private string $mercurePublicUrl)
    {

    }

    #[Route('/play/plhe', name: 'play_plhe', methods: ['GET'])]
    public function index(Security $security): Response {
        $userPlayer = $security->getUser()->getUserPlayer();

        return $this->render('play/index.html.twig', [
            'playerId' => $userPlayer->getPlayerId(),
        ]);
    }

    #[Route('/play/plhe', name: 'play_plhe_start', methods: ['POST'])]
    public function start(
        PokerGame $pokerGame,
        Security $security,
        MercureUpdate $mercureUpdate
    ): Response {
        $serviceManager = $pokerGame->getServiceManager();
        $userPlayer     = $security->getUser()->getUserPlayer();

        $response = $serviceManager->get(PlheSitController::class)->sit(playerId: $userPlayer->getPlayerId())->getContent();
        $response = $this->addMercureUrl($response, self::MERCURE_ACTION_TOPIC);
 
        $mercureUpdate->publish($response);

        return new Response($response);
    }

    #[Route('/action/plhe', name: 'action_plhe', methods: ['POST'])]
    public function action(Request $request, PokerGame $pokerGame, MercureUpdate $mercureUpdate): Response
    {
        $this->denyAccessUnlessGranted('action', [
            'class'   => PlayerAction::class,
            'request' => json_decode($request->getContent())
        ]);

        $serviceManager = $pokerGame->getServiceManager();
        $response       = $serviceManager->get(PlhePlayerActionController::class)->action($request)->getContent();
        $response       = $this->addMercureUrl($response, self::MERCURE_ACTION_TOPIC);

        $mercureUpdate->publish($response);

        return new Response($response);
    }

    private function addMercureUrl(string $jsonResponse, string $topic): string
    {
        $responseArray = json_decode($jsonResponse, true);
        $mergedArray   = array_merge($responseArray, [
            'mercureUrl' => $this->mercurePublicUrl . '?topic=' . $topic]
        );

        return json_encode($mergedArray);
    }
}
