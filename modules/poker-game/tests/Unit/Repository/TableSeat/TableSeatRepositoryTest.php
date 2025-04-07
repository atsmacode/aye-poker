<?php

namespace Atsmacode\PokerGame\Tests\Unit\Repository\TableSeat;

use Atsmacode\PokerGame\Tests\BaseTest;
use Atsmacode\PokerGame\Tests\HasGamePlay;

class TableSeatRepositoryTest extends BaseTest
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
     *
     * @return void
     */
    public function itCanSelectFirstActivePlayerAfterDealer()
    {
        $this->gamePlay->start($this->tableSeats->find([
            'id' => $this->gameState->getSeats()[0]['id'],
        ]));

        $tableSeat = $this->tableSeatRepo->playerAfterDealer(
            $this->gameState->handId(),
            $this->gameState->getSeats()[0]['id']
        );

        $this->assertEquals($this->gameState->getSeats()[1]['id'], $tableSeat->getId());
    }

        /**
     * @test
     *
     * @return void
     */
    public function itCanGetTheFirstAvailableSeat()
    {
        $table = $this->tables->create(['name' => 'Test Table', 'seats' => 1]);

        $this->tableSeats->create(['table_id' => $table->getId(), 'number' => 1]);

        $tableSeat = $this->tableSeatRepo->getFirstAvailableSeat($table->getId());

        $this->assertEquals($table->getId(), $tableSeat->getTableId());
    }

        /**
     * @test
     *
     * @return void
     */
    public function itCanGetAPlayersCurrentSeat()
    {
        $table = $this->tables->create(['name' => 'Test Table', 'seats' => 1]);
        $player = $this->createPlayer();
        $tableSeat = $this->tableSeats->create([
            'table_id' => $table->getId(),
            'player_id' => $player->getId(),
            'number' => 1,
        ]);

        $currentSeat = $this->tableSeatRepo->getCurrentPlayerSeat($player->getId());

        $this->assertEquals($tableSeat->getId(), $currentSeat->getId());
    }
}
