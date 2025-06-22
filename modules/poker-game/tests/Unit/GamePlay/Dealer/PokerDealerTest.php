<?php

namespace Atsmacode\PokerGame\Tests\Unit\GamePlay\Dealer;

use Atsmacode\CardGames\Constants\Card;
use Atsmacode\CardGames\Factory\CardFactory;
use Atsmacode\PokerGame\Models\Deck;
use Atsmacode\PokerGame\Tests\BaseTest;
use Atsmacode\PokerGame\Tests\HasHandFlow;

class PokerDealerTest extends BaseTest
{
    use HasHandFlow;

    private Deck $decks;

    protected function setUp(): void
    {
        parent::setUp();

        $this->isThreeHanded()
            ->setHand()
            ->setHandFlow();

        $this->decks = $this->container->build(Deck::class);
    }

    /**
     * @test
     *
     * @return void
     */
    public function itCanDealCardsToMultiplePlayersAtATable()
    {
        $handId = $this->testHand->getId();

        foreach ($this->testTable->getSeats() as $tableSeat) {
            $this->assertCount(0, $this->wholeCardRepo->getWholeCards($handId, $tableSeat['player_id']));
        }

        $this->pokerDealer->setDeck()->saveDeck($handId)->shuffle()->dealTo($this->testTable->getSeats(), 1, $handId);

        foreach ($this->testTable->getSeats() as $tableSeat) {
            $this->assertCount(1, $this->wholeCardRepo->getWholeCards($handId, $tableSeat['player_id']));
        }
    }

    /**
     * @test
     *
     * @return void
     */
    public function itCanDealAStreetCard()
    {
        $handId = $this->testHand->getId();
        $handStreet = $this->handStreets->create([
            'street_id' => $this->streets->find(['name' => 'Flop'])->getId(),
            'hand_id' => $this->hands->create(['game_id' => $this->testGame->getId()])->getId(),
        ]);

        $this->pokerDealer->setDeck()->saveDeck($handId)->dealStreetCards(
            $handId,
            $handStreet,
            1
        );

        $this->assertCount(1, $handStreet->cards());
    }

    /**
     * @test
     *
     * @return void
     */
    public function itCanDealASpecificStreetCard()
    {
        $handId = $this->testHand->getId();
        $handStreet = $this->handStreets->create([
            'street_id' => $this->streets->find(['name' => 'Flop'])->getId(),
            'hand_id' => $this->hands->create(['game_id' => $this->testGame->getId()])->getId(),
        ]);

        $card = CardFactory::create(Card::ACE_HEARTS);

        $this->pokerDealer->setDeck()->saveDeck($handId)->dealThisStreetCard(
            $handId,
            $card['rank'],
            $card['suit'],
            $handStreet
        );

        $this->assertContains($card['id'], array_column($handStreet->cards(), 'card_id'));
    }

    /**
     * @test
     *
     * @return void
     */
    public function itCanSaveADeck()
    {
        $this->pokerDealer->setDeck()->saveDeck($this->testHand->getId());

        $dealerDeck = $this->pokerDealer->getDeck();
        $savedDeck = $this->pokerDealer->setSavedDeck($this->testHand->getId());

        $this->assertSame($dealerDeck, $savedDeck->getDeck());
    }

    /**
     * @test
     *
     * @return void
     */
    public function itCanUpdateADeck()
    {
        $dealerDeck = $this->pokerDealer->setDeck()->saveDeck($this->testHand->getId())->getDeck();

        $this->pokerDealer->dealTo($this->testTable->getSeats(), 1, $this->testHand->getId());

        $updatedDeck = $this->pokerDealer->setSavedDeck($this->testHand->getId());

        $this->assertNotSame($dealerDeck, $updatedDeck->getDeck());
    }
}
