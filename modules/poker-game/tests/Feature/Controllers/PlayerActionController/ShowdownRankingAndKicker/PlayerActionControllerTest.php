<?php

namespace Atsmacode\PokerGame\Tests\Feature\Controllers\PlayerActionController\ShowdownRankingAndKicker;

use Atsmacode\PokerGame\HandStep\Start;
use Atsmacode\CardGames\Constants\Card;
use Atsmacode\PokerGame\Constants\HandType;
use Atsmacode\PokerGame\Models\HandStreetCard;
use Atsmacode\PokerGame\Tests\BaseTest;
use Atsmacode\PokerGame\Tests\HasActionPosts;
use Atsmacode\PokerGame\Tests\HasGamePlay;
use Atsmacode\PokerGame\Tests\HasStreets;

/**
 * In these tests, we are not calling GamePlay->start()
 * as we need to set specific whole cards.
 */
class PlayerActionControllerTest extends BaseTest
{
    use HasGamePlay, HasActionPosts, HasStreets;

    private HandStreetCard $handStreetCardModel;

    protected function setUp(): void
    {
        parent::setUp();

        $this->start               = $this->container->build(Start::class);
        $this->handStreetCardModel = $this->container->build(HandStreetCard::class);

        $this->isThreeHanded()
            ->setHand()
            ->setGamePlay()
            ->givenTheHandHasStarted();
    }

   /**
     * @test
     * @return void
     */
    public function highCardKingBeatsHighCardQueen()
    {
        $wholeCards = [
            [
                'player'  => $this->playerThree,
                'card_id' => Card::KING_SPADES_ID
            ],
            [
                'player'  => $this->playerThree,
                'card_id' => Card::THREE_DIAMONDS_ID
            ],
            [
                'player'  => $this->playerOne,
                'card_id' => Card::QUEEN_SPADES_ID
            ],
            [
                'player'  => $this->playerOne,
                'card_id' => Card::SEVEN_DIAMONDS_ID
            ],
        ];

        $this->setWholeCards($wholeCards);

        $flopCards = [
            ['card_id' => Card::FOUR_CLUBS_ID],
            ['card_id' => Card::JACK_SPADES_ID],
            ['card_id' => Card::DEUCE_CLUBS_ID],
        ];

        $this->setThisFlop($flopCards);

        $turnCard = ['card_id' => Card::NINE_DIAMONDS_ID];

        $this->setThisTurn($turnCard);

        $riverCard = ['card_id' => Card::TEN_SPADES_ID];

        $this->setThisRiver($riverCard);

        $this->gameState->setPlayers();

        $request  = $this->executeActionsToContinue();
        $response = $this->actionControllerResponse($request);

        $this->assertEquals($this->playerThree->getId(), $response['winner']['player']['player_id']);
        $this->assertEquals(HandType::HIGH_CARD['id'], $response['winner']['handType']['id']);
    }

    /**
     * @test
     * @return void
     */
    public function aceKingBeatsAceQueen()
    {
        $wholeCards = [
            [
                'player'  => $this->playerThree,
                'card_id' => Card::KING_SPADES_ID
            ],
            [
                'player'  => $this->playerThree,
                'card_id' => Card::ACE_DIAMONDS_ID
            ],
            [
                'player'  => $this->playerOne,
                'card_id' => Card::QUEEN_SPADES_ID
            ],
            [
                'player'  => $this->playerOne,
                'card_id' => Card::KING_DIAMONDS_ID
            ],
        ];

        $this->setWholeCards($wholeCards);

        $flopCards = [
            ['card_id' => Card::FOUR_CLUBS_ID],
            ['card_id' => Card::JACK_SPADES_ID],
            ['card_id' => Card::DEUCE_CLUBS_ID],
        ];

        $this->setThisFlop($flopCards);

        $turnCard = ['card_id' => Card::NINE_DIAMONDS_ID];

        $this->setThisTurn($turnCard);

        $riverCard = ['card_id' => Card::THREE_HEARTS_ID];

        $this->setThisRiver($riverCard);

        $this->gameState->setPlayers();

        $request  = $this->executeActionsToContinue();
        $response = $this->actionControllerResponse($request);

        $this->assertEquals($this->playerThree->getId(), $response['winner']['player']['player_id']);
        $this->assertEquals(HandType::HIGH_CARD['id'], $response['winner']['handType']['id']);
    }

    /**
     * @test
     * @return void
     */
    public function jacksBeatTens()
    {
        $wholeCards = [
            [
                'player'  => $this->playerThree,
                'card_id' => Card::QUEEN_SPADES_ID
            ],
            [
                'player'  => $this->playerThree,
                'card_id' => Card::JACK_SPADES_ID
            ],
            [
                'player'  => $this->playerOne,
                'card_id' => Card::KING_SPADES_ID
            ],
            [
                'player'  => $this->playerOne,
                'card_id' => Card::TEN_DIAMONDS_ID
            ],
        ];

        $this->setWholeCards($wholeCards);

        $flopCards = [
            ['card_id' => Card::JACK_HEARTS_ID],
            ['card_id' => Card::TEN_CLUBS_ID],
            ['card_id' => Card::DEUCE_CLUBS_ID],
        ];

        $this->setThisFlop($flopCards);

        $turnCard = ['card_id' => Card::NINE_DIAMONDS_ID];

        $this->setThisTurn($turnCard);

        $riverCard = ['card_id' => Card::FOUR_HEARTS_ID];

        $this->setThisRiver($riverCard);

        $this->gameState->setPlayers();

        $request  = $this->executeActionsToContinue();
        $response = $this->actionControllerResponse($request);

        $this->assertEquals($this->playerThree->getId(), $response['winner']['player']['player_id']);
        $this->assertEquals(HandType::PAIR['id'], $response['winner']['handType']['id']);
    }

    /**
     * @test
     * @return void
     */
    public function threeSevensBeatsThreeSixes()
    {
        $wholeCards = [
            [
                'player'  => $this->playerThree,
                'card_id' => Card::SEVEN_CLUBS_ID
            ],
            [
                'player'  => $this->playerThree,
                'card_id' => Card::SEVEN_HEARTS_ID
            ],
            [
                'player'  => $this->playerOne,
                'card_id' => Card::SIX_DIAMONDS_ID
            ],
            [
                'player'  => $this->playerOne,
                'card_id' => Card::SIX_HEARTS_ID
            ],
        ];

        $this->setWholeCards($wholeCards);

        $flopCards = [
            ['card_id' => Card::SEVEN_DIAMONDS_ID],
            ['card_id' => Card::SIX_SPADES_ID],
            ['card_id' => Card::DEUCE_CLUBS_ID],
        ];

        $this->setThisFlop($flopCards);

        $turnCard = ['card_id' => Card::NINE_DIAMONDS_ID];

        $this->setThisTurn($turnCard);

        $riverCard = ['card_id' => Card::FOUR_HEARTS_ID];

        $this->setThisRiver($riverCard);

        $this->gameState->setPlayers();

        $request  = $this->executeActionsToContinue();
        $response = $this->actionControllerResponse($request);

        $this->assertEquals($this->playerThree->getId(), $response['winner']['player']['player_id']);
        $this->assertEquals(HandType::TRIPS['id'], $response['winner']['handType']['id']);
    }

    /**
     * @test
     * @return void
     */
    public function kingHighStraightBeatsQueenHighStraight()
    {
        $wholeCards = [
            [
                'player'  => $this->playerThree,
                'card_id' => Card::KING_SPADES_ID
            ],
            [
                'player'  => $this->playerThree,
                'card_id' => Card::QUEEN_DIAMONDS_ID
            ],
            [
                'player'  => $this->playerOne,
                'card_id' => Card::QUEEN_SPADES_ID
            ],
            [
                'player'  => $this->playerOne,
                'card_id' => Card::JACK_SPADES_ID
            ],
        ];

        $this->setWholeCards($wholeCards);

        $flopCards = [
            ['card_id' => Card::JACK_HEARTS_ID],
            ['card_id' => Card::TEN_CLUBS_ID],
            ['card_id' => Card::DEUCE_CLUBS_ID],
        ];

        $this->setThisFlop($flopCards);

        $turnCard = ['card_id' => Card::NINE_DIAMONDS_ID];

        $this->setThisTurn($turnCard);

        $riverCard = ['card_id' => Card::EIGHT_SPADES_ID];

        $this->setThisRiver($riverCard);

        $this->gameState->setPlayers();

        $request  = $this->executeActionsToContinue();
        $response = $this->actionControllerResponse($request);

        $this->assertEquals($this->playerThree->getId(), $response['winner']['player']['player_id']);
        $this->assertEquals(HandType::STRAIGHT['id'], $response['winner']['handType']['id']);
    }

    /**
     * @test
     * @return void
     */
    public function aceHighFlushBeatsQueenHighFlush()
    {
        $wholeCards = [
            [
                'player'  => $this->playerThree,
                'card_id' => Card::ACE_HEARTS_ID
            ],
            [
                'player'  => $this->playerThree,
                'card_id' => Card::EIGHT_HEARTS_ID
            ],
            [
                'player'  => $this->playerOne,
                'card_id' => Card::QUEEN_HEARTS_ID
            ],
            [
                'player'  => $this->playerOne,
                'card_id' => Card::JACK_HEARTS_ID
            ],
        ];

        $this->setWholeCards($wholeCards);

        $flopCards = [
            ['card_id' => Card::KING_HEARTS_ID],
            ['card_id' => Card::FOUR_HEARTS_ID],
            ['card_id' => Card::DEUCE_CLUBS_ID],
        ];

        $this->setThisFlop($flopCards);

        $turnCard = ['card_id' => Card::SEVEN_HEARTS_ID];

        $this->setThisTurn($turnCard);

        $riverCard = ['card_id' => Card::EIGHT_SPADES_ID];

        $this->setThisRiver($riverCard);

        $this->gameState->setPlayers();

        $request  = $this->executeActionsToContinue();
        $response = $this->actionControllerResponse($request);

        $this->assertEquals($this->playerThree->getId(), $response['winner']['player']['player_id']);
        $this->assertEquals(HandType::FLUSH['id'], $response['winner']['handType']['id']);
    }

    /**
     * @test
     * @return void
     */
    public function aceHighFlushWithKingKickerBeatsAceHighFlushWithQueenKicker()
    {
        $wholeCards = [
            [
                'player'  => $this->playerThree,
                'card_id' => Card::KING_HEARTS_ID
            ],
            [
                'player'  => $this->playerThree,
                'card_id' => Card::EIGHT_HEARTS_ID
            ],
            [
                'player'  => $this->playerOne,
                'card_id' => Card::QUEEN_HEARTS_ID
            ],
            [
                'player'  => $this->playerOne,
                'card_id' => Card::JACK_HEARTS_ID
            ],
        ];

        $this->setWholeCards($wholeCards);

        $flopCards = [
            ['card_id' => Card::ACE_HEARTS_ID],
            ['card_id' => Card::FOUR_HEARTS_ID],
            ['card_id' => Card::DEUCE_CLUBS_ID],
        ];

        $this->setThisFlop($flopCards);

        $turnCard = ['card_id' => Card::SEVEN_HEARTS_ID];

        $this->setThisTurn($turnCard);

        $riverCard = ['card_id' => Card::THREE_HEARTS_ID];

        $this->setThisRiver($riverCard);

        $this->gameState->setPlayers();

        $request  = $this->executeActionsToContinue();
        $response = $this->actionControllerResponse($request);

        $this->assertEquals($this->playerThree->getId(), $response['winner']['player']['player_id']);
        $this->assertEquals(HandType::FLUSH['id'], $response['winner']['handType']['id']);
    }

    /**
     * @test
     * @return void
     */
    public function tensFullOfFoursBeatsTensFullOfThrees()
    {
        $wholeCards = [
            [
                'player' => $this->playerThree,
                'card_id' => Card::TEN_HEARTS_ID
            ],
            [
                'player' => $this->playerThree,
                'card_id' => Card::THREE_DIAMONDS_ID
            ],
            [
                'player' => $this->playerOne,
                'card_id' => Card::FOUR_HEARTS_ID
            ],
            [
                'player' => $this->playerOne,
                'card_id' => Card::TEN_CLUBS_ID
            ],
        ];

        $this->setWholeCards($wholeCards);

        $flopCards = [
            [ 'card_id' => Card::FOUR_SPADES_ID],
            ['card_id' => Card::TEN_SPADES_ID],
            ['card_id' => Card::THREE_HEARTS_ID],
        ];

        $this->setThisFlop($flopCards);

        $turnCard = ['card_id' => Card::JACK_SPADES_ID];

        $this->setThisTurn($turnCard);

        $riverCard = ['card_id' => Card::TEN_DIAMONDS_ID];

        $this->setThisRiver($riverCard);

        $this->gameState->setPlayers();

        $request  = $this->executeActionsToContinue();
        $response = $this->actionControllerResponse($request);

        $this->assertEquals($this->playerOne->getId(), $response['winner']['player']['player_id']);
        $this->assertEquals(HandType::FULL_HOUSE['id'], $response['winner']['handType']['id']);
    }

    /**
     * @test
     * @return void
     */
    public function fourTensBeatsFourNines()
    {
        $wholeCards = [
            [
                'player' => $this->playerThree,
                'card_id' => Card::TEN_HEARTS_ID
            ],
            [
                'player' => $this->playerThree,
                'card_id' => Card::TEN_CLUBS_ID
            ],
            [
                'player' => $this->playerOne,
                'card_id' => Card::NINE_HEARTS_ID
            ],
            [
                'player' => $this->playerOne,
                'card_id' => Card::NINE_CLUBS_ID
            ],
        ];

        $this->setWholeCards($wholeCards);

        $flopCards = [
            ['card_id' => Card::NINE_SPADES_ID],
            ['card_id' => Card::NINE_DIAMONDS_ID],
            ['card_id' => Card::THREE_HEARTS_ID],
        ];

        $this->setThisFlop($flopCards);

        $turnCard = ['card_id' => Card::TEN_SPADES_ID];

        $this->setThisTurn($turnCard);

        $riverCard = ['card_id' => Card::TEN_DIAMONDS_ID];

        $this->setThisRiver($riverCard);

        $this->gameState->setPlayers();

        $request  = $this->executeActionsToContinue();
        $response = $this->actionControllerResponse($request);

        $this->assertEquals($this->playerThree->getId(), $response['winner']['player']['player_id']);
        $this->assertEquals(HandType::QUADS['id'], $response['winner']['handType']['id']);
    }

    /**
     * @test
     * @return void
     */
    public function kingHighStraightFlushBeatsQueenHighStraightFlush()
    {
        $wholeCards = [
            [
                'player' => $this->playerThree,
                'card_id' => Card::THREE_DIAMONDS_ID
            ],
            [
                'player' => $this->playerThree,
                'card_id' => Card::KING_HEARTS_ID
            ],
            [
                'player' => $this->playerOne,
                'card_id' => Card::EIGHT_HEARTS_ID
            ],
            [
                'player' => $this->playerOne,
                'card_id' => Card::QUEEN_DIAMONDS_ID
            ],
        ];

        $this->setWholeCards($wholeCards);

        $flopCards = [
            ['card_id' => Card::TEN_HEARTS_ID],
            ['card_id' => Card::NINE_HEARTS_ID],
            ['card_id' => Card::QUEEN_HEARTS_ID],
        ];

        $this->setThisFlop($flopCards);

        $turnCard = ['card_id' => Card::JACK_HEARTS_ID];

        $this->setThisTurn($turnCard);

        $riverCard = ['card_id' => Card::DEUCE_CLUBS_ID];

        $this->setThisRiver($riverCard);

        $this->gameState->setPlayers();

        $request  = $this->executeActionsToContinue();
        $response = $this->actionControllerResponse($request);

        $this->assertEquals($this->playerThree->getId(), $response['winner']['player']['player_id']);
        $this->assertEquals(HandType::STRAIGHT_FLUSH['id'], $response['winner']['handType']['id']);
    }

    /**
     * @test
     * @return void
     */
    public function eightHighStraightBeatsTwoPlayersWithSevenHighStraight()
    {
        $wholeCards = [
            [
                'player'  => $this->playerThree,
                'card_id' => Card::ACE_DIAMONDS_ID
            ],
            [
                'player'  => $this->playerThree,
                'card_id' => Card::EIGHT_CLUBS_ID
            ],
            [
                'player'  => $this->playerTwo,
                'card_id' => Card::THREE_SPADES_ID
            ],
            [
                'player'  => $this->playerTwo,
                'card_id' => Card::SEVEN_CLUBS_ID
            ],
            [
                'player'  => $this->playerOne,
                'card_id' => Card::THREE_HEARTS_ID
            ],
            [
                'player'  => $this->playerOne,
                'card_id' => Card::DEUCE_DIAMONDS_ID
            ],
        ];

        $this->setWholeCards($wholeCards);

        $flopCards = [
            ['card_id' => Card::SEVEN_SPADES_ID],
            ['card_id' => Card::SIX_SPADES_ID],
            ['card_id' => Card::FIVE_HEARTS_ID],
        ];

        $this->setThisFlop($flopCards);

        $turnCard = ['card_id' => Card::FOUR_CLUBS_ID];

        $this->setThisTurn($turnCard);

        $riverCard = ['card_id' => Card::FIVE_DIAMONDS_ID];

        $this->setThisRiver($riverCard);

        $this->gameState->setPlayers();

        $request  = $this->executeActionsForThreePlayersToContinue();
        $response = $this->actionControllerResponse($request);

        $this->assertEquals($this->playerThree->getId(), $response['winner']['player']['player_id']);
        $this->assertEquals(HandType::STRAIGHT['id'], $response['winner']['handType']['id']);
    }

    private function executeActionsToContinue()
    {
        $this->givenPlayerOneCalls();
        $this->givenPlayerOneCanContinue();

        $this->givenPlayerTwoFolds();
        $this->givenPlayerTwoCanNotContinue();

        return $this->setPlayerThreeChecksPost();
    }

    private function executeActionsForThreePlayersToContinue()
    {
        $this->givenPlayerOneCalls();
        $this->givenPlayerOneCanContinue();

        $this->givenPlayerTwoCalls();
        $this->givenPlayerTwoCanContinue();

        return $this->setPlayerThreeChecksPost();
    }

    private function givenTheHandHasStarted()
    {
        $this->start->setGameState($this->gameState)
            ->initiateStreetActions()
            ->initiatePlayerStacks()
            ->setDealerAndBlindSeats()
            ->getGameState();

        return $this;
    }
}
