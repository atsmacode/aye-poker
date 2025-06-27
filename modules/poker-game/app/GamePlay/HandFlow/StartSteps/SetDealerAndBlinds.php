<?php

namespace Atsmacode\PokerGame\GamePlay\HandFlow\StartSteps;

use Atsmacode\PokerGame\Contracts\ProcessesGameState;
use Atsmacode\PokerGame\Models\HandStreet;
use Atsmacode\PokerGame\Models\PlayerAction;
use Atsmacode\PokerGame\Models\Street;
use Atsmacode\PokerGame\Models\TableSeat;
use Atsmacode\PokerGame\Services\Blinds\BlindService;
use Atsmacode\PokerGame\State\Game\GameState;

class SetDealerAndBlinds implements ProcessesGameState
{
    protected GameState $gameState;

    public function __construct(
        private Street $streets,
        private HandStreet $handStreets,
        private PlayerAction $playerActions,
        private TableSeat $tableSeats,
        private BlindService $blindService
    ) {
    }

    public function process(GameState $gameState): GameState
    {
        $handId = $gameState->handId();
        $tableId = $gameState->tableId();
        $firstStreet = $gameState->getStyle()->getStreets()[1]['name'];

        if (1 === $gameState->handStreetCount()) {
            $bigBlind = $this->playerActions->find(['hand_id' => $handId, 'big_blind' => 1]);

            if ($bigBlind->isNotEmpty()) {
                $bigBlind->update(['big_blind' => 0]);
            }
        }

        [
            'currentDealer' => $currentDealer,
            'dealer' => $dealer,
            'smallBlindSeat' => $smallBlindSeat,
            'bigBlindSeat' => $bigBlindSeat,
        ] = $this->getNextDealerAndBlinds($gameState);

        if ($currentDealer) {
            $currentDealerSeat = $this->tableSeats->find(['id' => $currentDealer['id'], 'table_id' => $tableId]);
            $currentDealerSeat->update(['is_dealer' => 0]);
        }

        $newDealerSeat = $this->tableSeats->find(['id' => $dealer['id'], 'table_id' => $tableId]);
        $newDealerSeat->update(['is_dealer' => 1]);

        $handStreetId = $this->handStreets->find([
            'street_id' => $this->streets->find(['name' => $firstStreet])->getId(),
            'hand_id' => $handId,
        ])->getId();

        $smallBlind = $this->playerActions->find([
            'player_id' => $smallBlindSeat['player_id'],
            'table_seat_id' => $smallBlindSeat['id'],
            'hand_street_id' => $handStreetId,
        ]);

        $bigBlind = $this->playerActions->find([
            'player_id' => $bigBlindSeat['player_id'],
            'table_seat_id' => $bigBlindSeat['id'],
            'hand_street_id' => $handStreetId,
        ]);

        $gameState->setLatestAction($bigBlind);

        $this->blindService->postBlinds($gameState, $smallBlind, $bigBlind);

        return $gameState;
    }

    private function getNextDealerAndBlinds(GameState $gameState): array
    {
        $currentDealer = $gameState->getDealer();

        $seats = $gameState->getSeats();
        $seatNumbers = array_column($seats, 'number');
        $total = count($seatNumbers);

        // Uses array indexes for modulo math
        $currentDealerIndex = $currentDealer
            ? array_search($currentDealer['number'], $seatNumbers, true)
            : -1;

        if (2 === $total) { // Heads up
            $dealerNumber = $seatNumbers[($currentDealerIndex + 1) % $total];
            $smallBlindNumber = $dealerNumber; // Dealer is always small blind heads up
            $bigBlindNumber = $seatNumbers[($currentDealerIndex + 2) % $total];
        } else {
            $dealerNumber = $seatNumbers[($currentDealerIndex + 1) % $total];
            $smallBlindNumber = $seatNumbers[($currentDealerIndex + 2) % $total];
            $bigBlindNumber = $seatNumbers[($currentDealerIndex + 3) % $total];
        }

        return [
            'currentDealer' => $currentDealer,
            'dealer' => $gameState->getSeat($dealerNumber),
            'smallBlindSeat' => $gameState->getSeat($smallBlindNumber),
            'bigBlindSeat' => $gameState->getSeat($bigBlindNumber),
        ];
    }
}
