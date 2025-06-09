<?php

namespace Atsmacode\PokerGame\Tests\Unit\Repository\TableSeat;

use Atsmacode\PokerGame\Tests\BaseTest;
use Atsmacode\PokerGame\Tests\HasHandFlow;

class TableSeatRepositoryTest extends BaseTest
{
    use HasHandFlow;

    protected function setUp(): void
    {
        parent::setUp();

        $this->isThreeHanded()
            ->setHand();
    }

    /**
     * @test
     *
     * @return void
     */
    public function itCanSelectFirstActivePlayerAfterDealer()
    {
        $this->givenCurrentDealerIs($this->playerOne->getId())
            ->setHandFlow();

        $this->handFlow->process($this->gameState);

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
        $this->setHandFlow();

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
        $this->setHandFlow();

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
