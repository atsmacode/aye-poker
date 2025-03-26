<?php

namespace Atsmacode\PokerGame\Tests\Unit\HandIdentifier;

use Atsmacode\PokerGame\HandIdentifier\HandIdentifier;
use Atsmacode\CardGames\Constants\Card;
use Atsmacode\CardGames\Constants\Rank;
use Atsmacode\CardGames\Factory\CardFactory;
use Atsmacode\PokerGame\Tests\BaseTest;

class HandIdentifierKickerTest extends BaseTest
{
    private HandIdentifier $handIdentifier;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->handIdentifier = new HandIdentifier();
    }

    /**
     * @test
     * @return void
     */
    public function itCanIdentifyTheKickerAndActiveRanksForAHighCardHand()
    {
        $wholeCards = [
            CardFactory::create(Card::KING_SPADES)
        ];

        $communityCards = [
            CardFactory::create(Card::QUEEN_HEARTS),
            CardFactory::create(Card::SEVEN_DIAMONDS),
            CardFactory::create(Card::TEN_CLUBS),
            CardFactory::create(Card::THREE_SPADES),
            CardFactory::create(Card::FOUR_DIAMONDS),
        ];

        $this->handIdentifier->identify($wholeCards, $communityCards);

        $this->assertEquals(
            Rank::KING['ranking'],
            $this->handIdentifier->getHighCard()
        );

        $this->assertEquals(
            CardFactory::create(Card::QUEEN_HEARTS)['ranking'],
            $this->handIdentifier->getIdentifiedHandType()['kicker']
        );

        $this->assertContains(
            Rank::KING['ranking'],
            $this->handIdentifier->getIdentifiedHandType()['activeCards']
        );
    }

    /**
     * @test
     * @return void
     */
    public function itCanIdentifyTheKickerAndActiveRanksForAPair()
    {
        $wholeCards = [
            CardFactory::create(Card::KING_SPADES),
            CardFactory::create(Card::NINE_DIAMONDS),
        ];

        $communityCards = [
            CardFactory::create(Card::QUEEN_HEARTS),
            CardFactory::create(Card::JACK_DIAMONDS),
            CardFactory::create(Card::FOUR_HEARTS),
            CardFactory::create(Card::NINE_CLUBS),
            CardFactory::create(Card::SEVEN_HEARTS),
        ];

        $this->handIdentifier->identify($wholeCards, $communityCards);

        $this->assertEquals(
            CardFactory::create(Card::KING_SPADES)['ranking'],
            $this->handIdentifier->getIdentifiedHandType()['kicker']
        );

        $this->assertContains(
            CardFactory::create(Card::NINE_DIAMONDS)['ranking'],
            $this->handIdentifier->getIdentifiedHandType()['activeCards']
        );

        $this->assertContains(
            CardFactory::create(Card::NINE_CLUBS)['ranking'],
            $this->handIdentifier->getIdentifiedHandType()['activeCards']
        );
    }
}
