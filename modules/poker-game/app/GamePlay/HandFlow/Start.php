<?php

namespace Atsmacode\PokerGame\GamePlay\HandFlow;

use Atsmacode\PokerGame\Contracts\ProcessesGameState;
use Atsmacode\PokerGame\Models\HandStreet;
use Atsmacode\PokerGame\Models\PlayerAction;
use Atsmacode\PokerGame\Models\Stack;
use Atsmacode\PokerGame\Models\Street;
use Atsmacode\PokerGame\Models\TableSeat;
use Atsmacode\PokerGame\Services\Blinds\BlindService;
use Atsmacode\PokerGame\State\Game\GameState;
use Psr\Container\ContainerInterface;

/**
 * Responsible for the actions required to start a new hand.
 */
class Start implements ProcessesGameState
{
    protected GameState $gameState;

    public function __construct(
        private ContainerInterface $container,
        private Street $streets,
        private HandStreet $handStreets,
        private PlayerAction $playerActions,
        private Stack $stacks,
        private TableSeat $tableSeats,
        private BlindService $blindService,
    ) {
    }

    public function process(GameState $gameState): GameState
    {
        $this->gameState = $gameState;

        $handId = $this->gameState->getHand()->getId();

        $this->initiateStreetActions()
            ->initiatePlayerStacks()
            ->setDealerAndBlindSeats();

        $this->gameState->loadPlayers()
            ->getGameDealer()
            ->shuffle()
            ->saveDeck($handId);

        $wholeCards = $this->gameState->getStyle()->getStreets()[1]['whole_cards'];

        if ($wholeCards && ! $this->gameState->testMode()) {
            $this->gameState->getGameDealer()
                ->dealTo($this->gameState->getSeats(), $wholeCards, $handId);
        }

        return $this->gameState;
    }

    private function initiateStreetActions(): self
    {
        $street = $this->handStreets->create([
            'street_id' => $this->streets->find(['name' => 'Pre-flop'])->getId(), 'hand_id' => $this->gameState->handId(),
        ]);

        foreach ($this->gameState->getSeats() as $seat) {
            $this->playerActions->create([
                'player_id' => $seat['player_id'],
                'hand_street_id' => $street->getId(),
                'table_seat_id' => $seat['id'],
                'hand_id' => $this->gameState->handId(),
                'active' => 1,
            ]);
        }

        return $this;
    }

    private function initiatePlayerStacks(): self
    {
        $tableId = $this->gameState->tableId();

        $tableStacks = [];

        foreach ($this->gameState->getSeats() as $seat) {
            $playerTableStack = $this->findPlayerStack($seat['player_id'], $tableId);

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

        $this->gameState->setStacks($tableStacks);

        return $this;
    }

    private function setDealerAndBlindSeats(): self
    {
        $handId = $this->gameState->handId();
        $tableId = $this->gameState->tableId();
        $firstStreet = $this->gameState->getStyle()->getStreets()[1]['name'];

        if (1 === $this->gameState->handStreetCount()) {
            $bigBlind = $this->playerActions->find(['hand_id' => $handId, 'big_blind' => 1]);

            if ($bigBlind->isNotEmpty()) {
                $bigBlind->update(['big_blind' => 0]);
            }
        }

        $currentDealer = $this->gameState->getDealer();

        [
            'currentDealer' => $currentDealer,
            'dealer' => $dealer,
            'smallBlindSeat' => $smallBlindSeat,
            'bigBlindSeat' => $bigBlindSeat,
        ] = $this->getNextDealerAndBlinds($currentDealer);

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

        $smallBlind = $this->findPlayerAction($smallBlindSeat['player_id'], $smallBlindSeat['id'], $handStreetId);
        $bigBlind = $this->findPlayerAction(
            $bigBlindSeat['player_id'],
            $bigBlindSeat['id'],
            $handStreetId
        );

        $this->gameState->setLatestAction($bigBlind);

        $this->blindService->postBlinds($this->gameState->getHand(), $smallBlind, $bigBlind, $this->gameState);

        return $this;
    }

    /** Needed a way to create unique instances of the model in the container */
    private function findPlayerAction(int $playerId, int $tableSeatId, int $handStreetId): PlayerAction
    {
        $playerAction = $this->container->build(PlayerAction::class); /* @phpstan-ignore  method.notFound */

        return $playerAction->find([
            'player_id' => $playerId,
            'table_seat_id' => $tableSeatId,
            'hand_street_id' => $handStreetId,
        ]);
    }

    /** Needed a way to create unique instances of the model in the container */
    private function findPlayerStack(int $playerId, int $tableId): ?Stack
    {
        $stack = $this->container->build(Stack::class); /* @phpstan-ignore method.notFound */

        return $stack->find([
            'player_id' => $playerId,
            'table_id' => $tableId,
        ]);
    }

    private function getNextDealerAndBlinds(?TableSeat $currentDealerSet = null): array
    {
        $currentDealer = $this->getDealer($currentDealerSet);
        $seats = $this->gameState->getSeats();
        $seatNumbers = array_column($seats, 'number');
        $total = count($seatNumbers);
        
        $currentDealerIndex = $currentDealer
            ? array_search($currentDealer['number'], $seatNumbers, true)
            : -1;

        if ($total === 2) { // Heads up
            $dealerNumber     = $seatNumbers[($currentDealerIndex + 1) % $total];
            $smallBlindNumber = $dealerNumber; // Dealer is always small blind heads up
            $bigBlindNumber   = $seatNumbers[($currentDealerIndex + 2) % $total];
        } else {
            $dealerNumber     = $seatNumbers[($currentDealerIndex + 1) % $total];
            $smallBlindNumber = $seatNumbers[($currentDealerIndex + 2) % $total];
            $bigBlindNumber   = $seatNumbers[($currentDealerIndex + 3) % $total];
        }
        
        return [
            'currentDealer' => $currentDealer,
            'dealer' => $this->gameState->getSeat($dealerNumber),
            'smallBlindSeat' => $this->gameState->getSeat($smallBlindNumber),
            'bigBlindSeat' => $this->gameState->getSeat($bigBlindNumber),
        ];
    }

    private function getDealer(?TableSeat $currentDealerSet = null): ?array
    {
        return $currentDealerSet
            ? $this->gameState->getSeat($currentDealerSet->getNumber())
            : $this->gameState->getDealer();
    }
}
