<?php

namespace Atsmacode\PokerGame\GamePlay\HandFlow;

use Atsmacode\PokerGame\Contracts\ProcessesGameState;
use Atsmacode\PokerGame\GamePlay\Showdown\Showdown as TheShowdown;
use Atsmacode\PokerGame\Services\Pots\PotService;
use Atsmacode\PokerGame\State\Game\GameState;

/**
 * Responsible for the actions required if the hand has reached a showdown.
 */
class Showdown implements ProcessesGameState
{
    protected GameState $gameState;
    
    public function __construct(private PotService $potService)
    {
    }

    public function process(GameState $gameState): GameState
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
