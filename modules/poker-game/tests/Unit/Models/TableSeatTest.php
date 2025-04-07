<?php

namespace Atsmacode\PokerGame\Tests\Unit\Models;

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
     *
     * @return void
     */
    public function aTableSeatCanBeUpdated()
    {
        $tableSeat = $this->tableSeats->find(['id' => $this->gameState->getSeats()[0]['id']]);

        $this->assertEquals(0, (int) $tableSeat->canContinue());

        $tableSeat->update(['can_continue' => 1]);

        $this->assertEquals(1, (int) $tableSeat->canContinue());
    }
}
