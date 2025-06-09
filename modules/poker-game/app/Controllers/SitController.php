<?php

namespace Atsmacode\PokerGame\Controllers;

use Atsmacode\PokerGame\Services\GamePlay\GamePlayService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class SitController
{
    public function __construct(private GamePlayService $gamePlay)
    {
    }

    public function sit(Request $request, ?int $playerId = null): Response
    {
        $response = $this->gamePlay->sit($request, $playerId);

        return new Response(json_encode($response));
    }
}
