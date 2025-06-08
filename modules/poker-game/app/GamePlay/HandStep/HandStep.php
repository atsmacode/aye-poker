<?php

namespace Atsmacode\PokerGame\GamePlay\HandStep;

use Atsmacode\PokerGame\State\Game\GameState;

/**
 * Handle's the unique logic required for each step of a hand based on the GameState.
 */
abstract class HandStep
{
    protected GameState $gameState;

    abstract public function handle(GameState $gameState): GameState;

    public function getGameState(): GameState
    {
        return $this->gameState;
    }

    public function setGameState(GameState $gameState): self
    {
        $this->gameState = $gameState;

        return $this;
    }
}
