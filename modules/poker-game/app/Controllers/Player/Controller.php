<?php

namespace Atsmacode\PokerGame\Controllers\Player;

use Atsmacode\PokerGame\Models\Player;
use Laminas\ServiceManager\ServiceManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Controller
{
    private Player $player;

    public function __construct(ServiceManager $container)
    {
        $this->player = $container->build(Player::class);
    }

    public function create(Request $request): Response
    {
        $requestContent = $request->toArray();

        $player = $this->player->find(['name' => $requestContent['name']]);

        if ($player->exists()) {
            return new Response(json_encode(['error' => 'Player with this name already exists.']));
        }

        $player = $this->player->create(['name' => $requestContent['name']]);

        return new Response(json_encode(['playerId' => $player->getId()]));
    }
}
