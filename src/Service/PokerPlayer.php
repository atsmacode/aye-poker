<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\UserPlayer;
use Atsmacode\PokerGame\Services\Players\PlayerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class PokerPlayer
{
    public function __construct(private PokerGame $pokerGame, private EntityManagerInterface $entityManager) {}

     public function create(Request $request, User $user, string $playerName): array|UserPlayer
     {
        $request  = new Request(content: json_encode(['name' => $playerName]));

        $response = $this->pokerGame
            ->get(PlayerService::class)
            ->create($request);

        $content = json_decode($response->getContent(), true);

        if (isset($content['error'])) {
            return $content;
        }

        $userPlayer = new UserPlayer();
        $userPlayer->setPlayerId($content['playerId']);
        $userPlayer->setUser($user);

        return $userPlayer;
    }
}