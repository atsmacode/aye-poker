<?php

namespace Atsmacode\PokerGame\Tests;

use Atsmacode\PokerGame\Constants\Action;
use Atsmacode\PokerGame\Enums\GameMode;
use Atsmacode\PokerGame\GamePlay\GameStyle\PotLimitHoldEm;
use Atsmacode\PokerGame\GamePlay\HandFlow\HandFlow;
use Atsmacode\PokerGame\GamePlay\HandFlow\Start;
use Atsmacode\PokerGame\Models\Game;
use Atsmacode\PokerGame\Models\Hand;
use Atsmacode\PokerGame\Models\Player;
use Atsmacode\PokerGame\Models\Table;
use Atsmacode\PokerGame\Models\TableSeat;
use Atsmacode\PokerGame\State\Game\GameState;

trait HasHandFlow
{
    private HandFlow $handFlow;
    private GameState $gameState;
    private Start $start;
    private Hand $testHand;
    private Player $playerOne;
    private Player $playerTwo;
    private Player $playerThree;
    private Player $playerFour;
    private Player $playerFive;
    private Player $playerSix;
    private TableSeat $tableSeatOne;
    private TableSeat $tableSeatTwo;
    private TableSeat $tableSeatThree;
    private TableSeat $tableSeatFour;
    private TableSeat $tableSeatFive;
    private TableSeat $tableSeatSix;
    private Table $testTable;
    private Game $testGame;

    private function createPlayer()
    {
        $player = $this->container->build(Player::class);

        return $player->create(['name' => $this->fake->unique()->name()]);
    }

    private function createTableSeat(int $tableId, int $playerId, int $number)
    {
        $tableSeat = $this->container->build(TableSeat::class);

        return $tableSeat->create([
            'table_id' => $tableId,
            'player_id' => $playerId,
            'number' => $number,
        ]);
    }

    private function setHandFlow()
    {
        $this->gameState = $this->container->build(GameState::class, [
            'hand' => isset($this->testHand) ? $this->testHand : null,
        ]);

        $this->handFlow = $this->container->build(HandFlow::class, [
            'game' => $this->container->get(PotLimitHoldEm::class),
            'gameState' => $this->gameState,
        ]);

        return $this;
    }

    private function setTable(int $seatCount)
    {
        $this->testTable = $this->tables->create(['name' => 'Test Table', 'seats' => $seatCount]);

        return $this;
    }

    private function setGame()
    {
        $this->testGame = $this->games->create([
            'table_id' => $this->testTable->getId(),
            'mode' => GameMode::REAL->value,
        ]);

        return $this;
    }

    private function setHand()
    {
        $this->setGame();

        $this->testHand = $this->hands->create(['game_id' => $this->testGame->getId()]);

        return $this;
    }

    private function isHeadsUp()
    {
        $this->setTable(2);

        $this->playerOne = $this->createPlayer();
        $this->playerTwo = $this->createPlayer();

        $this->tableSeatOne = $this->createTableSeat($this->testTable->getId(), $this->playerOne->getId(), 1);
        $this->tableSeatTwo = $this->createTableSeat($this->testTable->getId(), $this->playerTwo->getId(), 2);

        return $this;
    }

    private function isThreeHanded()
    {
        $this->setTable(3);

        $this->playerOne = $this->createPlayer();
        $this->playerTwo = $this->createPlayer();
        $this->playerThree = $this->createPlayer();

        $this->tableSeatOne = $this->createTableSeat($this->testTable->getId(), $this->playerOne->getId(), 1);
        $this->tableSeatTwo = $this->createTableSeat($this->testTable->getId(), $this->playerTwo->getId(), 2);
        $this->tableSeatThree = $this->createTableSeat($this->testTable->getId(), $this->playerThree->getId(), 3);

        return $this;
    }

    private function isFourHanded()
    {
        $this->testTable = $this->tables->create(['name' => 'Test Table', 'seats' => 4]);

        $this->playerOne = $this->createPlayer();
        $this->playerTwo = $this->createPlayer();
        $this->playerThree = $this->createPlayer();
        $this->playerFour = $this->createPlayer();

        $this->tableSeatOne = $this->createTableSeat($this->testTable->getId(), $this->playerOne->getId(), 1);
        $this->tableSeatTwo = $this->createTableSeat($this->testTable->getId(), $this->playerTwo->getId(), 2);
        $this->tableSeatThree = $this->createTableSeat($this->testTable->getId(), $this->playerThree->getId(), 3);
        $this->tableSeatFour = $this->createTableSeat($this->testTable->getId(), $this->playerFour->getId(), 4);

        $this->setHandFlow();

        return $this;
    }

    private function isSixHanded()
    {
        $this->testTable = $this->tables->create(['name' => 'Test Table', 'seats' => 6]);

        $this->playerOne = $this->createPlayer();
        $this->playerTwo = $this->createPlayer();
        $this->playerThree = $this->createPlayer();
        $this->playerFour = $this->createPlayer();
        $this->playerFive = $this->createPlayer();
        $this->playerSix = $this->createPlayer();

        $this->tableSeatOne = $this->createTableSeat($this->testTable->getId(), $this->playerOne->getId(), 1);
        $this->tableSeatTwo = $this->createTableSeat($this->testTable->getId(), $this->playerTwo->getId(), 2);
        $this->tableSeatThree = $this->createTableSeat($this->testTable->getId(), $this->playerThree->getId(), 3);
        $this->tableSeatFour = $this->createTableSeat($this->testTable->getId(), $this->playerFour->getId(), 4);
        $this->tableSeatFive = $this->createTableSeat($this->testTable->getId(), $this->playerFive->getId(), 5);
        $this->tableSeatSix = $this->createTableSeat($this->testTable->getId(), $this->playerSix->getId(), 6);

        $this->setHandFlow();

        return $this;
    }

    private function givenPlayerOneCanContinue()
    {
        $this->tableSeats->find(['id' => $this->gameState->getSeat(1)['id']])
            ->update([
                'can_continue' => 1,
            ]);
    }

    private function givenPlayerOneCanNotContinue()
    {
        $this->tableSeats->find(['id' => $this->gameState->getSeat(1)['id']])
            ->update([
                'can_continue' => 0,
            ]);
    }

    private function givenPlayerOneCalls()
    {
        $playerAction = $this->playerActionFactory->create(
            playerActionId: $this->gameState->getPlayer($this->playerOne->getId())['player_action_id'],
            handId: $this->gameState->handId(),
            actionId: Action::CALL_ID,
            betAmount: 50,
            active: 1,
        );

        $this->gameState->setLatestAction($playerAction);
    }

    private function givenPlayerOnePreviouslyCalled()
    {
        $playerAction = $this->playerActionFactory->create(
            playerActionId: $this->gameState->getPlayer($this->playerOne->getId())['player_action_id'],
            handId: $this->gameState->handId(),
            actionId: null,
            betAmount: 50,
            active: 1,
        );

        $this->gameState->setLatestAction($playerAction);
    }

    private function givenPlayerOneFolds()
    {
        $playerAction = $this->playerActionFactory->create(
            playerActionId: $this->gameState->getPlayer($this->playerOne->getId())['player_action_id'],
            handId: $this->gameState->handId(),
            actionId: Action::FOLD_ID,
            betAmount: null,
            active: 0,
        );

        $this->gameState->setLatestAction($playerAction);
    }

    private function givenPlayerOneRaisesBigBlind()
    {
        $playerAction = $this->playerActionFactory->create(
            playerActionId: $this->gameState->getPlayer($this->playerOne->getId())['player_action_id'],
            handId: $this->gameState->handId(),
            actionId: Action::RAISE_ID,
            betAmount: 100,
            active: 1,
        );

        $this->gameState->setLatestAction($playerAction);
    }

    private function givenPlayerTwoCalls()
    {
        $playerAction = $this->playerActionFactory->create(
            playerActionId: $this->gameState->getPlayer($this->playerTwo->getId())['player_action_id'],
            handId: $this->gameState->handId(),
            actionId: Action::CALL_ID,
            betAmount: 50,
            active: 1,
        );

        $this->gameState->setLatestAction($playerAction);
    }

    private function givenPlayerTwoFolds()
    {
        $playerAction = $this->playerActionFactory->create(
            playerActionId: $this->gameState->getPlayer($this->playerTwo->getId())['player_action_id'],
            handId: $this->gameState->handId(),
            actionId: Action::FOLD_ID,
            betAmount: null,
            active: 0,
        );

        $this->gameState->setLatestAction($playerAction);
    }

    private function givenPlayerTwoChecks()
    {
        $playerAction = $this->playerActionFactory->create(
            playerActionId: $this->gameState->getPlayer($this->playerTwo->getId())['player_action_id'],
            handId: $this->gameState->handId(),
            actionId: Action::CHECK_ID,
            betAmount: null,
            active: 1,
        );

        $this->gameState->setLatestAction($playerAction);
    }

    private function givenPlayerTwoPreviouslyChecked()
    {
        $playerAction = $this->playerActionFactory->create(
            playerActionId: $this->gameState->getPlayer($this->playerTwo->getId())['player_action_id'],
            handId: $this->gameState->handId(),
            actionId: null,
            betAmount: null,
            active: 1,
        );

        $this->gameState->setLatestAction($playerAction);
    }

    private function givenPlayerTwoCanContinue()
    {
        $this->tableSeats->find(['id' => $this->gameState->getSeat(2)['id']])
            ->update([
                'can_continue' => 1,
            ]);
    }

    private function givenPlayerTwoCanNotContinue()
    {
        $this->tableSeats->find(['id' => $this->gameState->getSeat(2)['id']])
            ->update([
                'can_continue' => 0,
            ]);
    }

    private function givenPlayerThreeCalls()
    {
        $playerAction = $this->playerActionFactory->create(
            playerActionId: $this->gameState->getPlayer($this->playerThree->getId())['player_action_id'],
            handId: $this->gameState->handId(),
            actionId: Action::CALL_ID,
            betAmount: 50,
            active: 1,
        );

        $this->gameState->setLatestAction($playerAction);
    }

    private function givenPlayerThreePreviouslyCalled()
    {
        $playerAction = $this->playerActionFactory->create(
            playerActionId: $this->gameState->getPlayer($this->playerThree->getId())['player_action_id'],
            handId: $this->gameState->handId(),
            actionId: null,
            betAmount: 50,
            active: 1,
        );

        $this->gameState->setLatestAction($playerAction);
    }

    private function givenPlayerThreeChecks()
    {
        $playerAction = $this->playerActionFactory->create(
            playerActionId: $this->gameState->getPlayer($this->playerThree->getId())['player_action_id'],
            handId: $this->gameState->handId(),
            actionId: Action::CHECK_ID,
            betAmount: null,
            active: 1,
        );

        $this->gameState->setLatestAction($playerAction);
    }

    private function givenPlayerThreeFolds()
    {
        $playerAction = $this->playerActionFactory->create(
            playerActionId: $this->gameState->getPlayer($this->playerThree->getId())['player_action_id'],
            handId: $this->gameState->handId(),
            actionId: Action::FOLD_ID,
            betAmount: null,
            active: 0,
        );

        $this->gameState->setLatestAction($playerAction);
    }

    private function givenPlayerThreeCanNotContinue()
    {
        $this->tableSeats->find(['id' => $this->gameState->getSeat(3)['id']])
            ->update([
                'can_continue' => 0,
            ]);
    }

    private function givenPlayerThreeCallsSmallBlind()
    {
        $playerAction = $this->playerActionFactory->create(
            playerActionId: $this->gameState->getPlayer($this->playerThree->getId())['player_action_id'],
            handId: $this->gameState->handId(),
            actionId: Action::CALL_ID,
            betAmount: 25,
            active: 1,
        );

        $this->gameState->setLatestAction($playerAction);
    }

    private function givenPlayerThreeRaises()
    {
        $playerAction = $this->playerActionFactory->create(
            playerActionId: $this->gameState->getPlayer($this->playerThree->getId())['player_action_id'],
            handId: $this->gameState->handId(),
            actionId: Action::RAISE_ID,
            betAmount: 100,
            active: 1,
        );

        $this->gameState->setLatestAction($playerAction);
    }

    private function givenPlayerThreeCanContinue()
    {
        $this->tableSeats->find(['id' => $this->gameState->getSeat(3)['id']])
            ->update([
                'can_continue' => 1,
            ]);
    }

    private function givenPlayerFourFolds()
    {
        $playerAction = $this->playerActionFactory->create(
            playerActionId: $this->gameState->getPlayer($this->playerFour->getId())['player_action_id'],
            handId: $this->gameState->handId(),
            actionId: Action::FOLD_ID,
            betAmount: null,
            active: 0,
        );

        $this->gameState->setLatestAction($playerAction);
    }

    private function givenPlayerFourCalls()
    {
        $playerAction = $this->playerActionFactory->create(
            playerActionId: $this->gameState->getPlayer($this->playerFour->getId())['player_action_id'],
            handId: $this->gameState->handId(),
            actionId: Action::CALL_ID,
            betAmount: 50.00,
            active: 1,
        );

        $this->gameState->setLatestAction($playerAction);
    }

    private function givenPlayerFourChecks()
    {
        $playerAction = $this->playerActionFactory->create(
            playerActionId: $this->gameState->getPlayer($this->playerFour->getId())['player_action_id'],
            handId: $this->gameState->handId(),
            actionId: Action::CHECK_ID,
            betAmount: null,
            active: 1,
        );

        $this->gameState->setLatestAction($playerAction);
    }

    private function givenPlayerFourRaises()
    {
        $playerAction = $this->playerActionFactory->create(
            playerActionId: $this->gameState->getPlayer($this->playerFour->getId())['player_action_id'],
            handId: $this->gameState->handId(),
            actionId: Action::RAISE_ID,
            betAmount: 100,
            active: 1,
        );

        $this->gameState->setLatestAction($playerAction);
    }

    private function givenPlayerFourCanContinue()
    {
        $this->tableSeats->find(['id' => $this->gameState->getSeat(4)['id']])
            ->update([
                'can_continue' => 1,
            ]);
    }

    private function givenPlayerFourCanNotContinue()
    {
        $this->tableSeats->find(['id' => $this->gameState->getSeat(4)['id']])
            ->update([
                'can_continue' => 0,
            ]);
    }

    private function givenPlayerFiveCalls()
    {
        $playerAction = $this->playerActionFactory->create(
            playerActionId: $this->gameState->getPlayer($this->playerFive->getId())['player_action_id'],
            handId: $this->gameState->handId(),
            actionId: Action::CALL_ID,
            betAmount: 50,
            active: 1,
        );

        $this->gameState->setLatestAction($playerAction);
    }

    private function givenPlayerFiveFolds()
    {
        $playerAction = $this->playerActionFactory->create(
            playerActionId: $this->gameState->getPlayer($this->playerFive->getId())['player_action_id'],
            handId: $this->gameState->handId(),
            actionId: Action::FOLD_ID,
            betAmount: null,
            active: 0,
        );

        $this->gameState->setLatestAction($playerAction);
    }

    private function givenPlayerFiveCanContinue()
    {
        $this->tableSeats->find(['id' => $this->gameState->getSeat(5)['id']])
            ->update([
                'can_continue' => 1,
            ]);
    }

    private function givenPlayerFiveCanNotContinue()
    {
        $this->tableSeats->find(['id' => $this->gameState->getSeat(5)['id']])
            ->update([
                'can_continue' => 0,
            ]);
    }

    private function givenPlayerSixFolds()
    {
        $playerAction = $this->playerActionFactory->create(
            playerActionId: $this->gameState->getPlayer($this->playerSix->getId())['player_action_id'],
            handId: $this->gameState->handId(),
            actionId: Action::FOLD_ID,
            betAmount: null,
            active: 0,
        );

        $this->gameState->setLatestAction($playerAction);
    }

    private function givenPlayerSixCanContinue()
    {
        $this->tableSeats->find(['id' => $this->gameState->getSeat(6)['id']])
            ->update([
                'can_continue' => 1,
            ]);
    }

    private function givenPlayerSixCanNotContinue()
    {
        $this->tableSeats->find(['id' => $this->gameState->getSeat(6)['id']])
            ->update([
                'can_continue' => 0,
            ]);
    }

    private function givenCurrentDealerIs(int $playerId)
    {
        $this->tableSeats->find(['player_id' => $playerId, 'table_id' => $this->testTable->getId()])
            ->update([
                'is_dealer' => 1,
            ]);

        return $this;
    }

    protected function setWholeCards($wholeCards)
    {
        foreach ($wholeCards as $card) {
            $this->wholeCards->create([
                'player_id' => $card['player']->getId(),
                'card_id' => $card['card_id'],
                'hand_id' => $this->gameState->handId(),
            ]);
        }
    }
}
