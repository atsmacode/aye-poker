<?php

namespace Atsmacode\PokerGame\Tests\Unit;

use Atsmacode\PokerGame\Tests\BaseTest;
use Atsmacode\PokerGame\Tests\HasGamePlay;

class TableSeatTest extends BaseTest
{
    use HasGamePlay;

    protected function setUp(): void
    {
        parent::setUp();

        $this->isThreeHanded()
            ->sethand()
            ->setGamePlay();
    }

    /**
     * @test
     * @return void
     */
    public function a_table_seat_can_be_updated()
    {
        $tableSeat = $this->tableSeatModel->find(['id' => $this->gameState->getSeats()[0]['id']]);

        $this->assertEquals(0, (int) $tableSeat->canContinue());

        $tableSeat->update(['can_continue' => 1]);

        $this->assertEquals(1, (int) $tableSeat->canContinue());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_select_first_active_player_after_dealer()
    {
        $this->gamePlay->start($this->tableSeatModel->find([
            'id' => $this->gameState->getSeats()[0]['id']
        ]));

        $tableSeat = $this->tableSeatModel->playerAfterDealer(
            $this->gameState->handId(),
            $this->gameState->getSeats()[0]['id']
        );

        $this->assertEquals($this->gameState->getSeats()[1]['id'], $tableSeat->getId());
    }

    /**
     * @test
     * @return void
     */
    public function itCanGetTheFirstAvailableSeat()
    {
        $table = $this->tableModel->create(['name' => 'Test Table', 'seats' => 1]);

        $this->tableSeatModel->create(['table_id' => $table->getId()]);

        $tableSeat = $this->tableSeatModel->getFirstAvailableSeat($table->getId());

        $this->assertEquals($table->getId(), $tableSeat->getContent()['table_id']);
    }

    /**
     * @test
     * @return void
     */
    public function itCanGetAPlayersCurrentSeat()
    {
        $table       = $this->tableModel->create(['name' => 'Test Table', 'seats' => 1]);
        $player      = $this->createPlayer(1);
        $tableSeat   = $this->tableSeatModel->create(['table_id' => $table->getId(), 'player_id' => $player->getId()]);
        $currentSeat = $this->tableSeatModel->getCurrentPlayerSeat($player->getId());

        $this->assertEquals($tableSeat->getId(), $currentSeat->getId());
    }
}
