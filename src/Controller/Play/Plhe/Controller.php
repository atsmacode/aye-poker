<?php

namespace App\Controller\Play\Plhe;

use App\Entity\UserPlayer;
use App\Service\PokerGame;
use Atsmacode\PokerGame\Controllers\PotLimitHoldEm\SitController as PlheSitController;
use Atsmacode\PokerGame\Controllers\PotLimitHoldEm\PlayerActionController as PlhePlayerActionController;
use Atsmacode\PokerGame\Models\PlayerAction;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class Controller extends AbstractController
{
    #[Route('/play/plhe', name: 'play_plhe', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('play/index.html.twig');
    }

    #[Route('/play/plhe', name: 'play_plhe_start', methods: ['POST'])]
    public function start(
        PokerGame $pokerGame,
        Security $security,
        EntityManagerInterface $entityManager
    ): Response {
        $serviceManager       = $pokerGame->getServiceManager();
        $userPlayerRepository = $entityManager->getRepository(UserPlayer::class);
        $user                 = $security->getUser();

        $userPlayer = $userPlayerRepository->findOneBy([
            'userId' => $user->getId()
        ]);

        $response = $serviceManager->get(PlheSitController::class)->sit(playerId: $userPlayer->getPlayerId())->getContent();

        return new Response($response);
    }

    #[Route('/action/plhe', name: 'action_plhe', methods: ['POST'])]
    public function action(Request $request, PokerGame $pokerGame): Response
    {
        $this->denyAccessUnlessGranted('action', [
            'class'   => PlayerAction::class,
            'request' => json_decode($request->getContent())
        ]);

        $serviceManager = $pokerGame->getServiceManager();
        $response       = $serviceManager->get(PlhePlayerActionController::class)->action($request)->getContent();

        return new Response($response);
    }
}
