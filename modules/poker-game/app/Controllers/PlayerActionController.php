<?php

namespace Atsmacode\PokerGame\Controllers;

use Atsmacode\PokerGame\Services\GamePlay\GamePlayService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class PlayerActionController
{
    public function __construct(private GamePlayService $gamePlay)
    {
    }

    public function action(Request $request): Response
    {
        $response = $this->gamePlay->action($request);

        return new Response(json_encode($response));
    }
}
