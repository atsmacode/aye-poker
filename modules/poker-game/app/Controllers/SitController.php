<?php

namespace Atsmacode\PokerGame\Controllers;

use Atsmacode\PokerGame\Services\Sit\SitService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class SitController
{
    public function __construct(private SitService $sitService)
    {
    }

    public function sit(Request $request, ?int $playerId = null): Response
    {
        $response = $this->sitService->sit($request, $playerId);

        return new Response(json_encode($response));
    }
}
