<?php

namespace Atsmacode\CardGames\Tests\Unit\Factory;

use Atsmacode\CardGames\Constants\Card;
use Atsmacode\CardGames\Factory\CardFactory;
use Atsmacode\CardGames\Tests\BaseTest;

class CardFactoryTest extends BaseTest
{
    private array $card;

    public function setUp(): void
    {
        parent::setUp();

        $this->card = CardFactory::create(Card::ACE_SPADES);
    }

    /**
     * @test
     *
     * @return void
     */
    public function aCardHasASuit()
    {
        $this->assertEquals('Spades', $this->card['suit']);
    }

    /**
     * @test
     *
     * @return void
     */
    public function aCardHasARank()
    {
        $this->assertEquals('Ace', $this->card['rank']);
    }

    /**
     * @test
     *
     * @return void
     */
    public function aCardHasARanking()
    {
        $this->assertEquals(1, $this->card['ranking']);
    }
}
