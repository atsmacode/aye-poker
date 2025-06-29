<?php

namespace App\Controller\Games;

use App\Form\CreateGameFormType;
use App\Service\PokerGame;
use Atsmacode\PokerGame\Models\Game;
use Atsmacode\PokerGame\Services\Games\GameService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    #[Route('/games/new', name: 'create_game')]
    public function create(Request $request, PokerGame $pokerGame): Response
    {
        $form = $this->createForm(CreateGameFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $game = $pokerGame
                ->get(GameService::class)
                ->create($form->getData());

            return $this->redirectToRoute('show_game', [
                'gameId' => $game->getId(),
                'tableId' => $game->getTableId()
            ]);
        }

        return $this->render('games/create.html.twig', ['form' => $form]);
    }

    #[Route('/games/{gameId}', name: 'show_game', methods: ['GET'])]
    public function index(Request $request, Security $security, PokerGame $pokerGame): Response {
        $gameId = $request->attributes->get('_route_params')['gameId'];
        $userPlayer = $security->getUser() ? $security->getUser()->getUserPlayer() : null;

        $game = $pokerGame
            ->get(Game::class)
            ->find(['id' => $gameId]);

        return $this->render('play/index.html.twig', [
            'playerId' => $userPlayer?->getPlayerId(),
            'gameId' => $gameId,
            'tableId' => $game->getTableId()
        ]);
    }
}
