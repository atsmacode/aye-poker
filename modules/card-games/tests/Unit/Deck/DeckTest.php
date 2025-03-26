<?php

namespace Atsmacode\CardGames\Tests\Unit\Deck;

use Atsmacode\CardGames\Deck\Deck;
use Atsmacode\CardGames\Tests\BaseTest;

class DeckTest extends BaseTest
{
    public function setUp(): void
    {
        parent::setUp();

        $this->deck = new Deck();
    }

    /**
     * @test
     * @return void
     */
    public function a_deck_can_be_instantiated()
    {
        $this->assertInstanceOf(Deck::class, $this->deck);
    }

    /**
     * @test
     * @return void
     */
    public function a_deck_has_52_cards()
    {
        $this->assertEquals(52, count($this->deck->cards));
    }

    /**
     * @test
     * @return void
     */
    public function a_deck_can_be_shuffled()
    {

        $deck = $this->deck->cards;
        $shuffled = $this->deck->cards;

        shuffle($shuffled);

        $this->assertNotSame($deck, $shuffled);

    }
}
