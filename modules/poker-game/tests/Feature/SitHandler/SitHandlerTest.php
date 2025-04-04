<?php

namespace Atsmacode\PokerGame\Tests\Feature\SitHandler;

use Atsmacode\PokerGame\Models\Player;
use Atsmacode\PokerGame\Models\Table;
use Atsmacode\PokerGame\Models\TableSeat;
use Atsmacode\PokerGame\SitHandler\SitHandler;
use Atsmacode\PokerGame\Tests\BaseTest;

class SitHandlerTest extends BaseTest
{
    private SitHandler $sitHandler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->table = $this->container->get(Table::class);
        $this->tableSeat = $this->container->get(TableSeat::class);
        $this->player = $this->container->get(Player::class);
        $this->sitHandler = $this->container->get(SitHandler::class);
    }

    /**
     * @test
     *
     * @return void
     */
    public function itCanSitAPlayerAtATable()
    {
        $table = $this->table->create(['name' => 'Test Table', 'seats' => 1]);

        $this->tableSeat->create(['table_id' => $table->getId(), 'number' => 1]);

        $player = $this->player->create(['name' => $this->fake->unique()->name()]);
        $tableSeat = $this->sitHandler->sit($player->getId(), $table->getId());

        $this->assertEquals($player->getId(), $tableSeat->getPlayerId());
    }
}
