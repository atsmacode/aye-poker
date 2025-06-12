<?php

namespace Atsmacode\PokerGame\GamePlay\HandFlow;

use Atsmacode\PokerGame\Contracts\ProcessesGameState;
use Atsmacode\PokerGame\Pipelines\GameStatePipeline;
use Atsmacode\PokerGame\State\Game\GameState;

/**
 * Responsible for the actions required to start a new hand.
 *
 * TODO: Consider extracting the private methods into dedicated hand flow classes.
 */
class Start implements ProcessesGameState
{
    protected GameState $gameState;

    public function __construct(private GameStatePipeline $pipeline)
    {
    }

    public function process(GameState $gameState): GameState
    {
        $handId = $gameState->getHand()->getId();
        $style = $gameState->getStyle();

        $gameState = $this->pipeline
            ->add($style->startSteps())
            ->run($gameState);

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
