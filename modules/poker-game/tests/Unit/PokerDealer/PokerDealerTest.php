<?php

namespace Atsmacode\PokerGame\Tests\Unit\PokerDealer;

use Atsmacode\CardGames\Constants\Card;
use Atsmacode\CardGames\Factory\CardFactory;
use Atsmacode\PokerGame\Models\Deck;
use Atsmacode\PokerGame\Tests\BaseTest;
use Atsmacode\PokerGame\Tests\HasGamePlay;

class PokerDealerTest extends BaseTest
{
    use HasGamePlay;

    private Deck $deckModel;

    protected function setUp(): void
    {
        parent::setUp();

        $this->isThreeHanded()
            ->setHand()
            ->setGamePlay();

        $this->deckModel = $this->container->build(Deck::class);
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
            $this->assertCount(0, $this->player->getWholeCards($handId, $tableSeat['player_id']));
        }

        $this->pokerDealer->setDeck()->saveDeck($handId)->shuffle()->dealTo($this->testTable->getSeats(), 1, $handId);

        foreach ($this->testTable->getSeats() as $tableSeat) {
            $this->assertCount(1, $this->player->getWholeCards($handId, $tableSeat['player_id']));
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
        $handStreet = $this->handStreet->create([
            'street_id' => $this->street->find(['name' => 'Flop'])->getId(),
            'hand_id' => $this->hand->create(['table_id' => $this->testTable->getId()])->getId(),
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
        $handStreet = $this->handStreet->create([
            'street_id' => $this->street->find(['name' => 'Flop'])->getId(),
            'hand_id' => $this->hand->create(['table_id' => $this->testTable->getId()])->getId(),
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
