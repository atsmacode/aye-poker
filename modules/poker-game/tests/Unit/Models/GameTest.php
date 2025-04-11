<?php

namespace Atsmacode\PokerGame\Tests\Unit\Models;

use Atsmacode\PokerGame\Tests\BaseTest;
use Atsmacode\PokerGame\Tests\HasGamePlay;

class GameTest extends BaseTest
{
    use HasGamePlay;

    protected function setUp(): void
    {
        parent::setUp();

        $this->isThreeHanded()
            ->setTable(3);
    }

    /**
     * @test
     *
     * @return void
     */
    public function aGameCanBeCreated()
    {
        $tableId = $this->testTable->getId();

        $game = $this->games->create(['table_id' => $tableId]);

        $this->assertEquals($tableId, $game->getTableId());
    }
}
