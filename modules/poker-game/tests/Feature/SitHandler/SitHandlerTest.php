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

        $this->tableModel     = $this->container->get(Table::class);
        $this->tableSeatModel = $this->container->get(TableSeat::class);
        $this->playerModel    = $this->container->get(Player::class);
        $this->sitHandler     = $this->container->get(SitHandler::class);
    }

    /**
     * @test
     * @return void
     */
    public function itCanSitAPlayerAtATable()
    {
        $table = $this->tableModel->create(['name' => 'Test Table', 'seats' => 1]);

        $this->tableSeatModel->create(['table_id' => $table->getId()]);

        $player    = $this->playerModel->create(['name' => 'Player 1']);
        $tableSeat = $this->sitHandler->sit($player->getId(), $table->getId());

        $this->assertEquals($player->getId(), $tableSeat->getPlayerId());
    }
}
