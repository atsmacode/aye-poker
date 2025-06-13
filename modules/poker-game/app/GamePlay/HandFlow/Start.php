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
        $startSteps = $gameState->getStyle()->startSteps();
        $gameState = $this->pipeline
            ->add($startSteps)
            ->run($gameState);

        return $gameState;
    }
}
