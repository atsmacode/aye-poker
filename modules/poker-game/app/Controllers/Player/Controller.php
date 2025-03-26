<?php

namespace Atsmacode\PokerGame\Controllers\Player;

use Atsmacode\PokerGame\Models\Player;
use Laminas\ServiceManager\ServiceManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Controller
{
    private Player $playerModel;

    public function __construct(private ServiceManager $container)
    {
        $this->playerModel = $container->build(Player::class);
    }

    public function create(Request $request): Response
    {
        $requestContent = $request->toArray();
        $player         = $this->playerModel->create(['name' => $requestContent['name']]);

        return new Response(json_encode(['playerId' => $player->getId()]));
    }
}
