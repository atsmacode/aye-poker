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
     * @return void
     */
    public function itCanDealCardsToMultiplePlayersAtATable()
    {
        $handId = $this->hand->getId();

        foreach($this->table->getSeats() as $tableSeat){
            $this->assertCount(0, $this->playerModel->getWholeCards($handId, $tableSeat['player_id']));
        }

        $this->pokerDealer->setDeck()->saveDeck($handId)->shuffle()->dealTo($this->table->getSeats(), 1, $handId);

        foreach($this->table->getSeats() as $tableSeat){
            $this->assertCount(1, $this->playerModel->getWholeCards($handId, $tableSeat['player_id']));
        }
    }

    /**
     * @test
     * @return void
     */
    public function itCanDealAStreetCard()
    {
        $handId     =  $this->hand->getId();
        $handStreet = $this->handStreetModel->create([
            'street_id' => $this->streetModel->find(['name' => 'Flop'])->getId(),
            'hand_id'   => $this->handModel->create(['table_id' => $this->table->getId()])->getId()
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
     * @return void
     */
    public function itCanDealASpecificStreetCard()
    {
        $handId     =  $this->hand->getId();
        $handStreet = $this->handStreetModel->create([
            'street_id' => $this->streetModel->find(['name' => 'Flop'])->getId(),
            'hand_id'   => $this->handModel->create(['table_id' => $this->table->getId()])->getId()
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
     * @return void
     */
    public function itCanSaveADeck()
    {
        $this->pokerDealer->setDeck()->saveDeck($this->hand->getId());

        $dealerDeck = $this->pokerDealer->getDeck();
        $savedDeck  = $this->pokerDealer->setSavedDeck($this->hand->getId());

        $this->assertSame($dealerDeck, $savedDeck->getDeck());
    }

    /**
     * @test
     * @return void
     */
    public function itCanUpdateADeck()
    {
        $dealerDeck = $this->pokerDealer->setDeck()->saveDeck($this->hand->getId())->getDeck();

        $this->pokerDealer->dealTo($this->table->getSeats(), 1, $this->hand->getId());

        $updatedDeck  = $this->pokerDealer->setSavedDeck($this->hand->getId());

        $this->assertNotSame($dealerDeck, $updatedDeck->getDeck());
    }
}
