<?php

namespace App\Controller\Play\Plhe;

use App\Service\MercureUpdate;
use App\Service\PokerGame;
use Atsmacode\PokerGame\Services\GamePlay\GamePlayService;
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

    #[Route('/play/plhe', name: 'play_plhe_start', methods: ['POST'])]
    public function start(
        PokerGame $pokerGame,
        Security $security,
        MercureUpdate $mercureUpdate,
        Request $request
    ): Response {
        $serviceManager = $pokerGame->getServiceManager();
        $userPlayer = $security->getUser()?->getUserPlayer();
        $request->attributes->set('playerId', $userPlayer?->getPlayerId());

        $response = $serviceManager->get(GamePlayService::class)->sit($request);
        
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
