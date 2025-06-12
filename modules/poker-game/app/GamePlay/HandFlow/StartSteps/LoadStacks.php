<?php

namespace Atsmacode\PokerGame\GamePlay\HandFlow\StartSteps;

use Atsmacode\PokerGame\Contracts\ProcessesGameState;
use Atsmacode\PokerGame\Models\Stack;
use Atsmacode\PokerGame\State\Game\GameState;

class LoadStacks implements ProcessesGameState
{
    protected GameState $gameState;

    public function __construct(private Stack $stacks)
    {
    }

    public function process(GameState $gameState): GameState
    {
        $tableId = $gameState->tableId();

        $tableStacks = [];

        foreach ($gameState->getSeats() as $seat) {
            $playerTableStack = $this->stacks->find(['player_id' => $seat['player_id'], 'table_id' => $tableId]);

            if ($playerTableStack) {
                $tableStacks[$seat['player_id']] = $playerTableStack;
            } else {
                $tableStacks[$seat['player_id']] = $this->stacks->create([
                    'amount' => 1000,
                    'player_id' => $seat['player_id'],
                    'table_id' => $tableId,
                ]);
            }
        }

        $gameState->setStacks($tableStacks);

        return $gameState;
    }
}
