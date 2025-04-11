<?php

namespace App\Controller\Games;

use App\Form\CreateGameFormType;
use App\Service\PokerGame;
use Atsmacode\PokerGame\Services\Games\GameService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    #[Route('/games/new', name: 'create_game')]
    public function create(): Response
    {
        $form = $this->createForm(CreateGameFormType::class);

        return $this->render('games/create.html.twig', ['form' => $form]);
    }
}
