<?php

namespace Atsmacode\PokerGame\Tests\Unit\Handlers\Sit;

use Atsmacode\PokerGame\Models\Player;
use Atsmacode\PokerGame\Models\Table;
use Atsmacode\PokerGame\Models\TableSeat;
use Atsmacode\PokerGame\Handlers\Sit\SitHandler;
use Atsmacode\PokerGame\Tests\BaseTest;

class SitHandlerTest extends BaseTest
{
    private SitHandler $sitHandler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tables = $this->container->get(Table::class);
        $this->tableSeats = $this->container->get(TableSeat::class);
        $this->players = $this->container->get(Player::class);
        $this->sitHandler = $this->container->get(SitHandler::class);
    }

    /**
     * @test
     *
     * @return void
     */
    public function itCanSitAPlayerAtATable()
    {
        $table = $this->tables->create(['name' => 'Test Table', 'seats' => 1]);

        $this->tableSeats->create(['table_id' => $table->getId(), 'number' => 1]);

        $player = $this->players->create(['name' => $this->fake->unique()->name()]);
        $tableSeat = $this->sitHandler->handle($player->getId(), $table->getId());

        $this->assertEquals($player->getId(), $tableSeat->getPlayerId());
    }
}
