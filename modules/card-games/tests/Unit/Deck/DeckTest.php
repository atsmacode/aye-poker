<?php

namespace Atsmacode\CardGames\Tests\Unit\Deck;

use Atsmacode\CardGames\Deck\Deck;
use Atsmacode\CardGames\Tests\BaseTest;

class DeckTest extends BaseTest
{
    private Deck $deck;

    public function setUp(): void
    {
        parent::setUp();

        $this->deck = new Deck();
    }

    /**
     * @test
     *
     * @return void
     */
    public function aDeckCanBeInstantiated()
    {
        $this->assertInstanceOf(Deck::class, $this->deck);
    }

    /**
     * @test
     *
     * @return void
     */
    public function aDeckHas52Cards()
    {
        $this->assertEquals(52, count($this->deck->cards));
    }

    /**
     * @test
     *
     * @return void
     */
    public function aDeckCanBeShuffled()
    {
        $deck = $this->deck->cards;
        $shuffled = $this->deck->cards;

        shuffle($shuffled);

        $this->assertNotSame($deck, $shuffled);
    }
}
