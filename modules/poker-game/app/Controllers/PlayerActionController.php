<?php

namespace Atsmacode\PokerGame\Controllers;

use Atsmacode\PokerGame\Services\GamePlayService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Psr\Container\ContainerInterface;

abstract class PlayerActionController
{
    public function __construct(private GamePlayService $gamePlayService)
    {    
    }

    public function action(Request $request): Response
    {
        $response = $this->gamePlayService->action($request);

        return new Response(json_encode($response));
    }
}
