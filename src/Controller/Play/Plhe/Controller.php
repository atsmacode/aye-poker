<?php

namespace App\Controller\Play\Plhe;

use App\Service\MercureUpdate;
use App\Service\PokerGame;
use Atsmacode\PokerGame\Services\GamePlay\GamePlayService;
use Atsmacode\PokerGame\Services\Sit\SitService;
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
        $userPlayer = $security->getUser()?->getUserPlayer();

        return $this->render('play/index.html.twig', [
            'playerId' => $userPlayer?->getPlayerId(),
            'gameId' => 1, // Default gameId
            'tableId' => 2 // Default tableId
        ]);
    }

    #[Route('/play/plhe', name: 'play_plhe_start', methods: ['POST'])]
    public function start(
        PokerGame $pokerGame,
        Security $security,
        MercureUpdate $mercureUpdate,
        Request $request
    ): Response {
        $serviceManager = $pokerGame->getServiceManager();
        $userPlayer     = $security->getUser() ? $security->getUser()->getUserPlayer() : null;
        $requestContent = json_decode($request->getContent(), true);

        $response = $serviceManager->get(SitService::class)->sit(
            playerId: $userPlayer?->getPlayerId(),
            gameId: $requestContent['gameId'],
            tableId: $requestContent['tableId']
        );
        
        $response = $this->addMercureUrlToArray($response, self::MERCURE_ACTION_TOPIC);
        $response = json_encode($response);
    
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
        $response       = $serviceManager->get(GamePlayService::class)->action($request);
        $response       = $this->addMercureUrlToArray($response, self::MERCURE_ACTION_TOPIC);
        $response       = json_encode($response);

        $mercureUpdate->publish($response);

        return new Response($response);
    }

    private function addMercureUrlToArray(array $response, string $topic): array
    {
        return array_merge($response, [
            'mercureUrl' => $this->mercurePublicUrl . '?topic=' . $topic]
        );
    }
}
