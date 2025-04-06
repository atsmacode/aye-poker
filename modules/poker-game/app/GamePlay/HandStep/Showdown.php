<?php

namespace Atsmacode\PokerGame\GamePlay\HandStep;

use Atsmacode\PokerGame\State\GameState\GameState;
use Atsmacode\PokerGame\Models\TableSeat;
use Atsmacode\PokerGame\Services\PotService\PotService;
use Atsmacode\PokerGame\GamePlay\Showdown\Showdown as TheShowdown;

/**
 * Responsible for the actions required if the hand has reached a showdown.
 */
class Showdown extends HandStep
{
    public function __construct(private PotService $potService)
    {
    }

    public function handle(GameState $gameState, ?TableSeat $currentDealer = null): GameState
    {
        $this->gameState = $gameState;

        $this->gameState->setPlayers();
        $this->gameState->setWholeCards(); /** This is done again in PlayerState, try reduce to 1 call */
        $winner = (new TheShowdown($this->gameState))->compileHands()->decideWinner();

        $this->gameState->setWinner($winner);

        $this->potService->awardPot(
            $winner['player']['stack'],
            $this->gameState->getPot(),
            $winner['player']['player_id'],
            $winner['player']['table_id']
        );

        $this->gameState->getHand()->complete();

        return $this->gameState;
    }
}
