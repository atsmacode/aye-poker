<?php

namespace Atsmacode\PokerGame\GamePlay\HandFlow\StartSteps;

use Atsmacode\PokerGame\Contracts\ProcessesGameState;
use Atsmacode\PokerGame\Models\HandStreet;
use Atsmacode\PokerGame\Models\PlayerAction;
use Atsmacode\PokerGame\Models\Street;
use Atsmacode\PokerGame\State\Game\GameState;

class CreatePlayerActions implements ProcessesGameState
{
    protected GameState $gameState;

    public function __construct(
        private Street $streets,
        private HandStreet $handStreets,
        private PlayerAction $playerActions,
    ) {
    }


    public function process(GameState $gameState): GameState
    {
        $street = $this->handStreets->create([
            'street_id' => $this->streets->find(['name' => 'Pre-flop'])->getId(), 'hand_id' => $gameState->handId(),
        ]);

        foreach ($gameState->getSeats() as $seat) {
            $this->playerActions->create([
                'player_id' => $seat['player_id'],
                'hand_street_id' => $street->getId(),
                'table_seat_id' => $seat['id'],
                'hand_id' => $gameState->handId(),
                'active' => 1,
            ]);
        }

        return $gameState;
    }
}
