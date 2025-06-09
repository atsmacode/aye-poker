<?php

namespace Atsmacode\PokerGame\GamePlay\HandStep;

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

        $this->gameState->setPlayers();

        $this->gameState->getGameDealer()
            ->shuffle()
            ->saveDeck($handId);

        if ($this->gameState->getStyle()->getStreets()[1]['whole_cards']) {
            $this->gameState->getGameDealer()->dealTo(
                $this->gameState->getSeats(),
                $this->gameState->getStyle()->getStreets()[1]['whole_cards'],
                $handId
            );
        }

        return $this->gameState;
    }

    public function initiateStreetActions(): self
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

    public function initiatePlayerStacks(): self
    {
        $tableStacks = [];

        foreach ($this->gameState->getSeats() as $seat) {
            $playerTableStack = $this->findPlayerStack($seat['player_id'], $this->gameState->tableId());

            if ($playerTableStack) {
                $tableStacks[$seat['player_id']] = $playerTableStack;
            } else {
                $tableStacks[$seat['player_id']] = $this->stacks->create([
                    'amount' => 1000,
                    'player_id' => $seat['player_id'],
                    'table_id' => $this->gameState->tableId(),
                ]);
            }
        }

        $this->gameState->setStacks($tableStacks);

        return $this;
    }

    public function setDealerAndBlindSeats(): self
    {
        if (1 === $this->gameState->handStreetCount()) {
            $bigBlind = $this->playerActions->find(['hand_id' => $this->gameState->handId(), 'big_blind' => 1]);

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
        ] = $this->isHeadsUp()
            ? $this->getNextDealerAndBlindSeatsHeadsUp($currentDealer)
            : $this->getNextDealerAndBlindSeats($currentDealer);

        if ($currentDealer) {
            $currentDealerSeat = $this->tableSeats->find(['id' => $currentDealer['id'], 'table_id' => $this->gameState->tableId()]);
            $currentDealerSeat->update(['is_dealer' => 0]);
        }

        $newDealerSeat = $this->tableSeats->find(['id' => $dealer['id'], 'table_id' => $this->gameState->tableId()]);
        $newDealerSeat->update(['is_dealer' => 1]);

        $handStreetId = $this->handStreets->find([
            'street_id' => $this->streets->find(['name' => $this->gameState->getStyle()->getStreets()[1]['name']])->getId(),
            'hand_id' => $this->gameState->handId(),
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

    private function noDealerIsSetOrThereIsNoSeatAfterTheCurrentDealer(?array $currentDealer): bool
    {
        return !$currentDealer || !$this->gameState->getSeat($currentDealer['id'] + 1);
    }

    private function thereAreThreeSeatsAfterTheCurrentDealer(?array $currentDealer): ?array
    {
        return $this->gameState->getSeat($currentDealer['id'] + 3);
    }

    private function thereAreTwoSeatsAfterTheCurrentDealer(?array $currentDealer): ?array
    {
        return $this->gameState->getSeat($currentDealer['id'] + 2);
    }

    private function getNextDealerAndBlindSeats(?TableSeat $currentDealerSet = null): array
    {
        $currentDealer = $this->setDealer($currentDealerSet);

        /* TODO: These must be called in order. Also will only work if all seats have a stack/player. */
        if ($this->noDealerIsSetOrThereIsNoSeatAfterTheCurrentDealer($currentDealer)) {
            $dealer = $this->gameState->getSeats()[0];
            $smallBlindSeat = $this->gameState->getSeat($dealer['id'] + 1);
            $bigBlindSeat = $this->gameState->getSeat($dealer['id'] + 2);
        } elseif ($this->thereAreThreeSeatsAfterTheCurrentDealer($currentDealer)) {
            $dealer = $this->gameState->getSeat($currentDealer['id'] + 1);
            $smallBlindSeat = $this->gameState->getSeat($dealer['id'] + 1);
            $bigBlindSeat = $this->gameState->getSeat($dealer['id'] + 2);
        } elseif ($this->thereAreTwoSeatsAfterTheCurrentDealer($currentDealer)) {
            $dealer = $this->gameState->getSeat($currentDealer['id'] + 1);
            $smallBlindSeat = $this->gameState->getSeat($dealer['id'] + 1);
            $bigBlindSeat = $this->gameState->getSeats()[0];
        } else {
            $dealer = $this->gameState->getSeat($currentDealer['id'] + 1);
            $smallBlindSeat = $this->gameState->getSeats()[0];
            $bigBlindSeat = $this->gameState->getSeats()[1];
        }

        return [
            'currentDealer' => $currentDealer,
            'dealer' => $dealer,
            'smallBlindSeat' => $smallBlindSeat,
            'bigBlindSeat' => $bigBlindSeat,
        ];
    }

    private function getNextDealerAndBlindSeatsHeadsUp(?TableSeat $currentDealerSet = null): array
    {
        $currentDealer = $this->setDealer($currentDealerSet);

        if ($this->noDealerIsSetOrThereIsNoSeatAfterTheCurrentDealer($currentDealer)) {
            $dealer = $this->gameState->getSeats()[0];
            $smallBlindSeat = $this->gameState->getSeat($dealer['id']);
            $bigBlindSeat = $this->gameState->getSeat($dealer['id'] + 1);
        } else {
            $dealer = $this->gameState->getSeat($currentDealer['id'] + 1);
            $smallBlindSeat = $this->gameState->getSeats()[1];
            $bigBlindSeat = $this->gameState->getSeats()[0];
        }

        return [
            'currentDealer' => $currentDealer,
            'dealer' => $dealer,
            'smallBlindSeat' => $smallBlindSeat,
            'bigBlindSeat' => $bigBlindSeat,
        ];
    }

    private function isHeadsUp(): bool
    {
        return 2 === count($this->gameState->getSeats());
    }

    private function setDealer(?TableSeat $currentDealerSet = null): ?array
    {
        return $currentDealerSet
            ? $this->gameState->getSeat($currentDealerSet->getId())
            : $this->gameState->getDealer();
    }

    /**
     * TODO: Added these for showdown unit testing purposes, consider removing.
     */
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
