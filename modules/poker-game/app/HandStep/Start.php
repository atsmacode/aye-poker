<?php

namespace Atsmacode\PokerGame\HandStep;

use Atsmacode\PokerGame\BetHandler\BetHandler;
use Atsmacode\PokerGame\GameState\GameState;
use Atsmacode\PokerGame\Models\HandStreet;
use Atsmacode\PokerGame\Models\PlayerAction;
use Atsmacode\PokerGame\Models\Stack;
use Atsmacode\PokerGame\Models\Street;
use Atsmacode\PokerGame\Models\TableSeat;
use Psr\Container\ContainerInterface;

/**
 * Responsible for the actions required to start a new hand.
 */
class Start extends HandStep
{
    public function __construct(
        private ContainerInterface $container,
        private Street             $streetModel,
        private HandStreet         $handStreetModel,
        private PlayerAction       $playerActionModel,
        private Stack              $stackModel,
        private TableSeat          $tableSeatModel,
        private BetHandler         $betHandler
    ) {}
    
    public function handle(GameState $gameState, TableSeat $currentDealer = null): GameState
    {
        $this->gameState = $gameState;
        $handId          = $this->gameState->getHand()->getId();

        $this->initiateStreetActions()
            ->initiatePlayerStacks()
            ->setDealerAndBlindSeats($currentDealer);
            
        $this->gameState->setPlayers();

        $this->gameState->getGameDealer()
            ->shuffle()
            ->saveDeck($handId);

        if($this->gameState->getGame()->streets[1]['whole_cards']){
            $this->gameState->getGameDealer()->dealTo(
                $this->gameState->getSeats(),
                $this->gameState->getGame()->streets[1]['whole_cards'],
                $handId
            );
        }

        return $this->gameState;
    }

    public function initiateStreetActions(): self
    {
        $street = $this->handStreetModel->create([
            'street_id' => $this->streetModel->find(['name' => 'Pre-flop'])->getId(), 'hand_id' => $this->gameState->handId()
        ]);

        foreach($this->gameState->getSeats() as $seat){
            $this->playerActionModel->create([
                'player_id'      => $seat['player_id'],
                'hand_street_id' => $street->getId(),
                'table_seat_id'  => $seat['id'],
                'hand_id'        => $this->gameState->handId(),
                'active'         => 1
            ]);
        }

        return $this;
    }

    public function initiatePlayerStacks(): self
    {
        $tableStacks = [];

        foreach($this->gameState->getSeats() as $seat){
            /** Looks like the count() check was added as there's only 1 table being handled. */
            $playerTableStack = $this->findPlayerStack($seat['player_id'], $this->gameState->tableId());

            if (0 === count($playerTableStack->getContent())) {
                $tableStacks[$seat['player_id']] = $this->stackModel->create([
                    'amount' => 1000,
                    'player_id' => $seat['player_id'],
                    'table_id' => $this->gameState->tableId()
                ]);
            } else {
                $tableStacks[$seat['player_id']] = $playerTableStack;
            }
        }

        $this->gameState->setStacks($tableStacks);

        return $this;
    }

    public function setDealerAndBlindSeats($currentDealer = null): self
    {
        if ($this->gameState->handStreetCount() === 1) {
            $bigBlind = $this->playerActionModel->find(['hand_id' => $this->gameState->handId(), 'big_blind' => 1]);

            if ($bigBlind->isNotEmpty()) { $bigBlind->update(['big_blind' => 0]); }
        }

        [
            'currentDealer'      => $currentDealer,
            'dealer'             => $dealer,
            'smallBlindSeat'     => $smallBlindSeat,
            'bigBlindSeat'       => $bigBlindSeat
        ] = $this->isHeadsUp() 
            ? $this->getNextDealerAndBlindSeatsHeadsUp($currentDealer)
            : $this->getNextDealerAndBlindSeats($currentDealer);

        if ($currentDealer) {
            $currentDealerSeat = $this->tableSeatModel->find(['id' => $currentDealer['id'], 'table_id' => $this->gameState->tableId()]);
            $currentDealerSeat->update(['is_dealer'  => 0]);
        }

        $newDealerSeat = $this->tableSeatModel->find(['id' => $dealer['id'], 'table_id' => $this->gameState->tableId()]);
        $newDealerSeat->update(['is_dealer'  => 1]);

        $handStreetId = $this->handStreetModel->find([
            'street_id'  => $this->streetModel->find(['name' => $this->gameState->getGame()->streets[1]['name']])->getId(),
            'hand_id' => $this->gameState->handId()
        ])->getId();

        $smallBlind = $this->findPlayerAction($smallBlindSeat['player_id'], $smallBlindSeat['id'], $handStreetId); 
        $bigBlind   = $this->findPlayerAction(
            $bigBlindSeat['player_id'],
            $bigBlindSeat['id'],
            $handStreetId
        ); 
        
        $this->gameState->setLatestAction($bigBlind);

        $this->betHandler->postBlinds($this->gameState->getHand(), $smallBlind, $bigBlind, $this->gameState);

        return $this;
    }

    /** Needed a way to create unique instances of the model in the container */
    private function findPlayerAction(int $playerId, int $tableSeatId, int $handStreetId)
    {
        $playerActionModel = $this->container->build(PlayerAction::class);

        return $playerActionModel->find([
            'player_id'      => $playerId,
            'table_seat_id'  => $tableSeatId,
            'hand_street_id' => $handStreetId,
        ]);
    }

    /** Needed a way to create unique instances of the model in the container */
    private function findPlayerStack(int $playerId, int $tableId)
    {
        $stackModel = $this->container->build(Stack::class);

        return $stackModel->find([
            'player_id' => $playerId,
            'table_id'  => $tableId,
        ]);
    }

    /**
     * @param TableSeat|false $currentDealer
     */
    private function noDealerIsSetOrThereIsNoSeatAfterTheCurrentDealer($currentDealer)
    {
        return !$currentDealer || !$this->gameState->getSeat($currentDealer['id'] + 1);
    }

    /**
     * @param TableSeat|false $currentDealer
     */
    private function thereAreThreeSeatsAfterTheCurrentDealer($currentDealer)
    {
        return $this->gameState->getSeat($currentDealer['id'] + 3);
    }

    /**
     * @param TableSeat|false $currentDealer
     */
    private function thereAreTwoSeatsAfterTheCurrentDealer($currentDealer)
    {
        return $this->gameState->getSeat($currentDealer['id'] + 2);
    }

    /**
     * @param TableSeat|false $currentDealer
     */
    private function thereIsOneSeatAfterTheDealer($currentDealer)
    {
        return $this->gameState->getSeat($currentDealer['id'] + 1);
    }

    private function getNextDealerAndBlindSeats(?TableSeat $currentDealerSet = null): array
    {
        $currentDealer = $this->setDealer($currentDealerSet);

        /** TODO: These must be called in order. Also will only work if all seats have a stack/player.*/
        if ($this->noDealerIsSetOrThereIsNoSeatAfterTheCurrentDealer($currentDealer)) {
            
            $dealer         = $this->gameState->getSeats()[0];
            $smallBlindSeat = $this->gameState->getSeat($dealer['id'] + 1);
            $bigBlindSeat   = $this->gameState->getSeat($dealer['id'] + 2);

        } else if ($this->thereAreThreeSeatsAfterTheCurrentDealer($currentDealer)) {

            $dealer         = $this->gameState->getSeat($currentDealer['id'] + 1);
            $smallBlindSeat = $this->gameState->getSeat($dealer['id'] + 1);
            $bigBlindSeat   = $this->gameState->getSeat($dealer['id'] + 2);

        } else if ($this->thereAreTwoSeatsAfterTheCurrentDealer($currentDealer)) {

            $dealer         = $this->gameState->getSeat($currentDealer['id'] + 1);
            $smallBlindSeat = $this->gameState->getSeat($dealer['id'] + 1);
            $bigBlindSeat   = $this->gameState->getSeats()[0];

        } else {

            $dealer         = $this->gameState->getSeat($currentDealer['id'] + 1);
            $smallBlindSeat = $this->gameState->getSeats()[0];
            $bigBlindSeat   = $this->gameState->getSeats()[1];

        }

        return [
            'currentDealer'  => $currentDealer,
            'dealer'         => $dealer,
            'smallBlindSeat' => $smallBlindSeat,
            'bigBlindSeat'   => $bigBlindSeat
        ];
    }

    private function getNextDealerAndBlindSeatsHeadsUp(?TableSeat $currentDealerSet = null): array
    {
        $currentDealer = $this->setDealer($currentDealerSet);

        if ($this->noDealerIsSetOrThereIsNoSeatAfterTheCurrentDealer($currentDealer)) {

            $dealer         = $this->gameState->getSeats()[0];
            $smallBlindSeat = $this->gameState->getSeat($dealer['id']);
            $bigBlindSeat   = $this->gameState->getSeat($dealer['id'] + 1);

        } else {

            $dealer         = $this->gameState->getSeat($currentDealer['id'] + 1);
            $smallBlindSeat = $this->gameState->getSeats()[1];
            $bigBlindSeat   = $this->gameState->getSeats()[0];

        }

        return [
            'currentDealer'  => $currentDealer,
            'dealer'         => $dealer,
            'smallBlindSeat' => $smallBlindSeat,
            'bigBlindSeat'   => $bigBlindSeat
        ];
    }

    private function isHeadsUp()
    {
        return 2 === count($this->gameState->getSeats());
    }

    private function setDealer(?TableSeat $currentDealerSet = null)
    {
        return $currentDealerSet 
            ? $this->gameState->getSeat($currentDealerSet->getId()) 
            : $this->gameState->getDealer();
    }
}
