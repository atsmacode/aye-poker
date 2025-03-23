<?php

namespace App\Controller\Play\Plhe;

use App\Entity\User;
use App\Entity\UserPlayer;
use App\Service\MercureUpdate;
use App\Service\PokerGame;
use Atsmacode\PokerGame\Controllers\PotLimitHoldEm\SitController as PlheSitController;
use Atsmacode\PokerGame\Controllers\PotLimitHoldEm\PlayerActionController as PlhePlayerActionController;
use Atsmacode\PokerGame\Models\PlayerAction;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;

class Controller extends AbstractController
{
    const MERCURE_ACTION_TOPIC = 'player_action';

    #[Route('/play/plhe', name: 'play_plhe', methods: ['GET'])]
    public function index(
        Security $security,
        EntityManagerInterface $entityManager
    ): Response {
        $userPlayer = $this->getUserPlayer($entityManager, $security->getUser());

        return $this->render('play/index.html.twig', [
            'playerId' => $userPlayer->getPlayerId(),
        ]);
    }

    #[Route('/play/plhe', name: 'play_plhe_start', methods: ['POST'])]
    public function start(
        PokerGame $pokerGame,
        Security $security,
        EntityManagerInterface $entityManager,
        MercureUpdate $mercureUpdate
    ): Response {
        $serviceManager = $pokerGame->getServiceManager();
        $userPlayer     = $this->getUserPlayer($entityManager, $security->getUser());

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

    private function getUserPlayer(EntityManagerInterface $entityManager, User $user): UserPlayer
    {
        $userPlayerRepository = $entityManager->getRepository(UserPlayer::class);

        $userPlayer = $userPlayerRepository->findOneBy([
            'userId' => $user->getId()
        ]);

        return $userPlayer;
    }

    private function addMercureUrl(string $jsonResponse, string $topic): string
    {
        $responseArray = json_decode($jsonResponse, true);
        $mergedArray   = array_merge($responseArray, [
            'mercureUrl' => $this->getParameter('mercure.default_hub') . '?topic=' . $topic]
        );

        return json_encode($mergedArray);
    }
}
