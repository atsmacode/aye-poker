<?php

namespace Atsmacode\PokerGame\GamePlay\HandFlow\StartSteps;

use Atsmacode\PokerGame\Contracts\ProcessesGameState;
use Atsmacode\PokerGame\State\Game\GameState;

class DealCards implements ProcessesGameState
{
    protected GameState $gameState;

    public function process(GameState $gameState): GameState
    {
        $handId = $gameState->getHand()->getId();
        $style = $gameState->getStyle();

        $gameState->loadPlayers()
            ->getGameDealer()
            ->shuffle()
            ->saveDeck($handId);

        $wholeCards = $style->getStreets()[1]['whole_cards'];

        if ($wholeCards && !$gameState->testMode()) {
            $gameState
                ->getGameDealer()
                ->dealTo($gameState->getSeats(), $wholeCards, $handId);
        }

        return $gameState;
    }
}
