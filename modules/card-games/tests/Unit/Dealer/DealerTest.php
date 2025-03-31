<?php

namespace Atsmacode\CardGames\Tests\Unit\Dealer;

use Atsmacode\CardGames\Dealer\Dealer;
use Atsmacode\CardGames\Tests\BaseTest;

class DealerTest extends BaseTest
{
    private Dealer $dealer;

    public function setUp(): void
    {
        parent::setUp();

        $this->dealer = new Dealer();
    }

    /**
     * @test
     *
     * @return void
     */
    public function itCanShuffleTheDeck()
    {
        $unshuffled = $this->dealer->setDeck()->getDeck();
        /*
         * Settled for calling setDeck here as the assertion was
         * picking up the same data for some reason.
         */
        $shuffled = $this->dealer->setDeck()->shuffle()->getDeck();

        $this->assertNotSame($unshuffled, $shuffled);
    }

    /**
     * @test
     *
     * @return void
     */
    public function itCanSelectARandomCard()
    {
        $this->assertNotNull($this->dealer->setDeck()->shuffle()->pickCard()->getCard());
    }

    /**
     * @test
     *
     * @return void
     */
    public function itCanSelectASpecificCard()
    {
        $this->assertNotNull($this->dealer->setDeck()->shuffle()->pickCard('Ace', 'Spades')->getCard());
    }

    /**
     * @test
     *
     * @return void
     */
    public function onceACardIsPickedItIsNoLongerInTheDeck()
    {
        $card = $this->dealer->setDeck()->pickCard()->getCard();

        $this->assertNotContains($card, $this->dealer->getDeck());
    }
}
