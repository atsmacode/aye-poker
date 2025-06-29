<?php

namespace Atsmacode\PokerGame\Services\Players;

use Atsmacode\PokerGame\Models\Player;
use Atsmacode\PokerGame\Repository\Players\PlayerRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PlayerService
{
    public function __construct(
        private PlayerRepository $playerRepo,
        private Player $players,
    ) {
    }

    public function getTestPlayers(): ?array
    {
        return $this->playerRepo->getTestPlayers();
    }

    public function create(Request $request): Response
    {
        $requestContent = $request->toArray();

        $player = $this->players->find(['name' => $requestContent['name']]);

        if ($player) {
            return new Response(json_encode(['error' => 'Player with this name already exists.']));
        }

        $player = $this->players->create(['name' => $requestContent['name']]);

        return new Response(json_encode(['playerId' => $player->getId()]));
    }
}
