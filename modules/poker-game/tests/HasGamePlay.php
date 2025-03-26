<?php

namespace Atsmacode\PokerGame\Tests;

use Atsmacode\PokerGame\Constants\Action;
use Atsmacode\PokerGame\Game\PotLimitHoldEm;
use Atsmacode\PokerGame\GamePlay\GamePlay;
use Atsmacode\PokerGame\GameState\GameState;
use Atsmacode\PokerGame\HandStep\Start;
use Atsmacode\PokerGame\Models\Hand;
use Atsmacode\PokerGame\Models\Player;
use Atsmacode\PokerGame\Models\TableSeat;

trait HasGamePlay
{
    private GamePlay  $gamePlay;
    private GameState $gameState;
    private Start     $start;
    private Hand      $hand;
    private Player    $playerOne;
    private Player    $playerTwo;
    private Player    $playerThree;
    private Player    $playerFour;
    private Player    $playerFive;
    private Player    $playerSix;
    private TableSeat $tableSeatOne;
    private TableSeat $tableSeatTwo;
    private TableSeat $tableSeatThree;
    private TableSeat $tableSeatFour;
    private TableSeat $tableSeatFive;
    private TableSeat $tableSeatSix;
    
    private function createPlayer(int $player)
    {
        $playerModel = $this->container->build(Player::class);

        return $playerModel->create(['name'  => 'Player ' . $player]);
    }

    private function createTableSeat(int $tableId, int $playerId, int $number)
    {
        $tableSeatModel = $this->container->build(TableSeat::class);

        return $tableSeatModel->create([
            'table_id'  => $tableId,
            'player_id' => $playerId,
            'number'    => $number,
        ]);
    }

    private function setGamePlay()
    {
        $this->gameState = $this->container->build(GameState::class, [
            'hand' => isset($this->hand) ? $this->hand : null,
        ]);
        
        $this->gamePlay = $this->container->build(GamePlay::class, [
            'game'      => $this->container->get(PotLimitHoldEm::class),
            'gameState' => $this->gameState,
        ]);

        return $this;
    }

    private function setTable(int $seatCount)
    {
        $this->table = $this->tableModel->create(['name' => 'Test Table', 'seats' => $seatCount]);

        return $this;
    }

    private function setHand()
    {
        $this->hand = $this->handModel->create(['table_id' => $this->table->getId()]);

        return $this;
    }

    private function isHeadsUp()
    {
        $this->setTable(2);

        $this->playerOne = $this->createPlayer(1);
        $this->playerTwo = $this->createPlayer(2);

        $this->tableSeatOne   = $this->createTableSeat($this->table->getId(), $this->playerOne->getId(), 1);
        $this->tableSeatTwo   = $this->createTableSeat($this->table->getId(), $this->playerTwo->getId(), 2);

        return $this;
    }

    private function isThreeHanded()
    {
        $this->setTable(3);

        $this->playerOne = $this->createPlayer(1);
        $this->playerTwo = $this->createPlayer(2);
        $this->playerThree = $this->createPlayer(3);

        $this->tableSeatOne   = $this->createTableSeat($this->table->getId(), $this->playerOne->getId(), 1);
        $this->tableSeatTwo   = $this->createTableSeat($this->table->getId(), $this->playerTwo->getId(), 2);
        $this->tableSeatThree = $this->createTableSeat($this->table->getId(), $this->playerThree->getId(), 3);

        return $this;
    }

    private function isFourHanded()
    {
        $this->table = $this->tableModel->create(['name' => 'Test Table', 'seats' => 4]);

        $this->playerOne = $this->createPlayer(1);
        $this->playerTwo = $this->createPlayer(2);
        $this->playerThree = $this->createPlayer(3);
        $this->playerFour = $this->createPlayer(4);

        $this->tableSeatOne   = $this->createTableSeat($this->table->getId(), $this->playerOne->getId(), 1);
        $this->tableSeatTwo   = $this->createTableSeat($this->table->getId(), $this->playerTwo->getId(), 2);
        $this->tableSeatThree = $this->createTableSeat($this->table->getId(), $this->playerThree->getId(), 3);
        $this->tableSeatFour  = $this->createTableSeat($this->table->getId(), $this->playerFour->getId(), 4);

        $this->setGamePlay();

        return $this;
    }

    private function isSixHanded()
    {
        $this->table = $this->tableModel->create(['name' => 'Test Table', 'seats' => 6]);

        $this->playerOne = $this->createPlayer(1);
        $this->playerTwo = $this->createPlayer(2);
        $this->playerThree = $this->createPlayer(3);
        $this->playerFour = $this->createPlayer(4);
        $this->playerFive = $this->createPlayer(5);
        $this->playerSix = $this->createPlayer(6);

        $this->tableSeatOne   = $this->createTableSeat($this->table->getId(), $this->playerOne->getId(), 1);
        $this->tableSeatTwo   = $this->createTableSeat($this->table->getId(), $this->playerTwo->getId(), 2);
        $this->tableSeatThree = $this->createTableSeat($this->table->getId(), $this->playerThree->getId(), 3);
        $this->tableSeatFour  = $this->createTableSeat($this->table->getId(), $this->playerFour->getId(), 4);
        $this->tableSeatFive  = $this->createTableSeat($this->table->getId(), $this->playerFive->getId(), 5);
        $this->tableSeatSix   = $this->createTableSeat($this->table->getId(), $this->playerSix->getId(), 6);

        $this->setGamePlay();

        return $this;
    }

    private function givenPlayerOneCanContinue()
    {
        $this->tableSeatModel->find(['id' => $this->gameState->getSeats()[0]['id']])
            ->update([
                'can_continue' => 1
            ]);
    }

    private function givenPlayerOneCanNotContinue()
    {
        $this->tableSeatModel->find(['id' => $this->gameState->getSeats()[0]['id']])
            ->update([
                'can_continue' => 0
            ]);
    }

    private function givenPlayerOneCalls()
    {
        $playerAction = $this->playerActionFactory->create(
            playerActionId: $this->gameState->getPlayers()[0]['player_action_id'],
            handId:         $this->gameState->handId(),
            actionId:       Action::CALL_ID,
            betAmount:      50,
            active:         1,
        );

        $this->gameState->setLatestAction($playerAction);
    }

    private function givenPlayerOnePreviouslyCalled()
    {
        $playerAction = $this->playerActionFactory->create(
            playerActionId: $this->gameState->getPlayers()[0]['player_action_id'],
            handId:         $this->gameState->handId(),
            actionId:       null,
            betAmount:      50,
            active:         1,
        );

        $this->gameState->setLatestAction($playerAction);
    }

    private function givenPlayerOneFolds()
    {
        $playerAction = $this->playerActionFactory->create(
            playerActionId: $this->gameState->getPlayers()[0]['player_action_id'],
            handId:         $this->gameState->handId(),
            actionId:       Action::FOLD_ID,
            betAmount:      null,
            active:         0,
        );

        $this->gameState->setLatestAction($playerAction);
    }

    private function givenPlayerOneRaisesBigBlind()
    {
        $playerAction = $this->playerActionFactory->create(
            playerActionId: $this->gameState->getPlayers()[0]['player_action_id'],
            handId:         $this->gameState->handId(),
            actionId:       Action::RAISE_ID,
            betAmount:      100,
            active:         1,
        );

        $this->gameState->setLatestAction($playerAction);
    }

    private function givenPlayerTwoCalls()
    {
        $playerAction = $this->playerActionFactory->create(
            playerActionId: $this->gameState->getPlayers()[1]['player_action_id'],
            handId:         $this->gameState->handId(),
            actionId:       Action::CALL_ID,
            betAmount:      50,
            active:         1,
        );

        $this->gameState->setLatestAction($playerAction);
    }

    private function givenPlayerTwoFolds()
    {
        $playerAction = $this->playerActionFactory->create(
            playerActionId: $this->gameState->getPlayers()[1]['player_action_id'],
            handId:         $this->gameState->handId(),
            actionId:       Action::FOLD_ID,
            betAmount:      null,
            active:         0,
        );

        $this->gameState->setLatestAction($playerAction);
    }

    private function givenPlayerTwoChecks()
    {
        $playerAction = $this->playerActionFactory->create(
            playerActionId: $this->gameState->getPlayers()[1]['player_action_id'],
            handId:         $this->gameState->handId(),
            actionId:       Action::CHECK_ID,
            betAmount:      null,
            active:         1,
        );

        $this->gameState->setLatestAction($playerAction);
    }

    private function givenPlayerTwoPreviouslyChecked()
    {
        $playerAction = $this->playerActionFactory->create(
            playerActionId: $this->gameState->getPlayers()[1]['player_action_id'],
            handId:         $this->gameState->handId(),
            actionId:       null,
            betAmount:      null,
            active:         1,
        );

        $this->gameState->setLatestAction($playerAction);
    }

    private function givenPlayerTwoCanContinue()
    {
        $this->tableSeatModel->find(['id' => $this->gameState->getSeats()[1]['id']])
            ->update([
                'can_continue' => 1
            ]);
    }

    private function givenPlayerTwoCanNotContinue()
    {
        $this->tableSeatModel->find(['id' => $this->gameState->getSeats()[1]['id']])
            ->update([
                'can_continue' => 0
            ]);
    }

    private function givenPlayerThreeCalls()
    {
        $playerAction = $this->playerActionFactory->create(
            playerActionId: $this->gameState->getPlayers()[2]['player_action_id'],
            handId:         $this->gameState->handId(),
            actionId:       Action::CALL_ID,
            betAmount:      50,
            active:         1,
        );

        $this->gameState->setLatestAction($playerAction);
    }

    private function givenPlayerThreePreviouslyCalled()
    {
        $playerAction = $this->playerActionFactory->create(
            playerActionId: $this->gameState->getPlayers()[2]['player_action_id'],
            handId:         $this->gameState->handId(),
            actionId:       null,
            betAmount:      50,
            active:         1,
        );

        $this->gameState->setLatestAction($playerAction);
    }

    private function givenPlayerThreeChecks()
    {
        $playerAction = $this->playerActionFactory->create(
            playerActionId: $this->gameState->getPlayers()[2]['player_action_id'],
            handId:         $this->gameState->handId(),
            actionId:       Action::CHECK_ID,
            betAmount:      null,
            active:         1,
        );

        $this->gameState->setLatestAction($playerAction);
    }

    private function givenPlayerThreeFolds()
    {
        $playerAction = $this->playerActionFactory->create(
            playerActionId: $this->gameState->getPlayers()[2]['player_action_id'],
            handId:         $this->gameState->handId(),
            actionId:       Action::FOLD_ID,
            betAmount:      null,
            active:         0,
        );

        $this->gameState->setLatestAction($playerAction);
    }

    private function givenPlayerThreeCanNotContinue()
    {
        $this->tableSeatModel->find(['id' => $this->gameState->getSeats()[2]['id']])
            ->update([
                'can_continue' => 0
            ]);
    }

    private function givenPlayerThreeCallsSmallBlind()
    {
        $playerAction = $this->playerActionFactory->create(
            playerActionId: $this->gameState->getPlayers()[2]['player_action_id'],
            handId:         $this->gameState->handId(),
            actionId:       Action::CALL_ID,
            betAmount:      25,
            active:         1,
        );

        $this->gameState->setLatestAction($playerAction);
    }

    private function givenPlayerThreeRaises()
    {
        $playerAction = $this->playerActionFactory->create(
            playerActionId: $this->gameState->getPlayers()[2]['player_action_id'],
            handId:         $this->gameState->handId(),
            actionId:       Action::RAISE_ID,
            betAmount:      100,
            active:         1,
        );

        $this->gameState->setLatestAction($playerAction);
    }

    private function givenPlayerThreeCanContinue()
    {
        $this->tableSeatModel->find(['id' => $this->gameState->getSeats()[2]['id']])
            ->update([
                'can_continue' => 1
            ]);
    }

    private function givenPlayerFourFolds()
    {
        $playerAction = $this->playerActionFactory->create(
            playerActionId: $this->gameState->getPlayers()[3]['player_action_id'],
            handId:         $this->gameState->handId(),
            actionId:       Action::FOLD_ID,
            betAmount:      null,
            active:         0,
        );

        $this->gameState->setLatestAction($playerAction);
    }

    private function givenPlayerFourCalls()
    {
        $playerAction = $this->playerActionFactory->create(
            playerActionId: $this->gameState->getPlayers()[3]['player_action_id'],
            handId:         $this->gameState->handId(),
            actionId:       Action::CALL_ID,
            betAmount:      50.00,
            active:         1,
        );

        $this->gameState->setLatestAction($playerAction);
    }

    private function givenPlayerFourChecks()
    {
        $playerAction = $this->playerActionFactory->create(
            playerActionId: $this->gameState->getPlayers()[3]['player_action_id'],
            handId:         $this->gameState->handId(),
            actionId:       Action::CHECK_ID,
            betAmount:      null,
            active:         1,
        );

        $this->gameState->setLatestAction($playerAction);
    }

    private function givenPlayerFourRaises()
    {
        $playerAction = $this->playerActionFactory->create(
            playerActionId: $this->gameState->getPlayers()[3]['player_action_id'],
            handId:         $this->gameState->handId(),
            actionId:       Action::RAISE_ID,
            betAmount:      100,
            active:         1,
        );

        $this->gameState->setLatestAction($playerAction);
    }

    private function givenPlayerFourCanContinue()
    {
        $this->tableSeatModel->find(['id' => $this->gameState->getSeats()[3]['id']])
            ->update([
                'can_continue' => 1
            ]);
    }

    private function givenPlayerFourCanNotContinue()
    {
        $this->tableSeatModel->find(['id' => $this->gameState->getSeats()[3]['id']])
            ->update([
                'can_continue' => 0
            ]);
    }

    private function givenPlayerFiveCalls()
    {
        $playerAction = $this->playerActionFactory->create(
            playerActionId: $this->gameState->getPlayers()[4]['player_action_id'],
            handId:         $this->gameState->handId(),
            actionId:       Action::CALL_ID,
            betAmount:      50,
            active:         1,
        );

        $this->gameState->setLatestAction($playerAction);
    }

    private function givenPlayerFiveFolds()
    {
        $playerAction = $this->playerActionFactory->create(
            playerActionId: $this->gameState->getPlayers()[4]['player_action_id'],
            handId:         $this->gameState->handId(),
            actionId:       Action::FOLD_ID,
            betAmount:      null,
            active:         0,
        );

        $this->gameState->setLatestAction($playerAction);
    }

    private function givenPlayerFiveCanContinue()
    {
        $this->tableSeatModel->find(['id' => $this->gameState->getSeats()[4]['id']])
            ->update([
                'can_continue' => 1
            ]);
    }

    private function givenPlayerFiveCanNotContinue()
    {
        $this->tableSeatModel->find(['id' => $this->gameState->getSeats()[4]['id']])
            ->update([
                'can_continue' => 0
            ]);
    }

    private function givenPlayerSixFolds()
    {
        $playerAction = $this->playerActionFactory->create(
            playerActionId: $this->gameState->getPlayers()[5]['player_action_id'],
            handId:         $this->gameState->handId(),
            actionId:       Action::FOLD_ID,
            betAmount:      null,
            active:         0,
        );

        $this->gameState->setLatestAction($playerAction);
    }

    private function givenPlayerSixCanContinue()
    {
        $this->tableSeatModel->find(['id' => $this->gameState->getSeats()[5]['id']])
            ->update([
                'can_continue' => 1
            ]);
    }

    private function givenPlayerSixCanNotContinue()
    {
        $this->tableSeatModel->find(['id' => $this->gameState->getSeats()[5]['id']])
            ->update([
                'can_continue' => 0
            ]);
    }

    protected function setWholeCards($wholeCards)
    {
        foreach($wholeCards as $card){
            $this->wholeCardModel->create([
                'player_id' => $card['player']->getId(),
                'card_id'   => $card['card_id'],
                'hand_id'   => $this->gameState->handId()
            ]);
        }
    }
}