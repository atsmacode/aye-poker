<?php

namespace Atsmacode\PokerGame\Controllers;

use Atsmacode\PokerGame\Models\TableSeat;
use Atsmacode\PokerGame\Services\SitService;
use Symfony\Component\HttpFoundation\Response;

abstract class SitController
{
    public function __construct(private SitService $sitService)
    {
    }

    /**
     * TODO Change this to accept Request.
     */
    public function sit(
        ?int $tableId = null,
        ?TableSeat $currentDealer = null,
        ?int $playerId = null,
    ): Response {
        $response = $this->sitService->sit(
            $tableId,
            $currentDealer,
            $playerId
        );

        return new Response(json_encode($response));
    }
}
