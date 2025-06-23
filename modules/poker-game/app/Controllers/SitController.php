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

    public function sit(Request $request): Response
    {
        $response = $this->gamePlay->sit($request);

        return new Response(json_encode($response));
    }
}
