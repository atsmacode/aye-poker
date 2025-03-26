<?php

namespace Atsmacode\PokerGame\Tests\Feature\Controllers\SitController\HeadsUp;

use Atsmacode\PokerGame\Tests\BaseTest;
use Atsmacode\PokerGame\Tests\HasActionPosts;
use Atsmacode\PokerGame\Tests\HasGamePlay;

class SitControllerTest extends BaseTest
{
    use HasGamePlay, HasActionPosts;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @test
     * @return void
     */
    public function itCanStartTheGame()
    {
        $this->isHeadsUp()->setGamePlay();
        
        $response = $this->sitControllerResponse();

        // The small blind was posted by the dealer
        $this->assertEquals(25, $response['players'][1]['bet_amount']);
        $this->assertEquals('Bet', $response['players'][1]['action_name']);
        $this->assertEquals(1, $response['players'][1]['is_dealer']);

        // The big blind was posted
        $this->assertEquals(50, $response['players'][2]['bet_amount']);
        $this->assertEquals('Bet', $response['players'][2]['action_name']);

        // Each player in the hand has 2 whole cards
        foreach($response['players'] as $player){
            $this->assertCount(2, $player['whole_cards']);
        }
    }

    /**
     * @test
     * @return void
     */
    public function thePreFlopActionWillInitiallyBeOnTheDealer()
    {
        $this->isHeadsUp()->setGamePlay();

        $response = $this->sitControllerResponse();

        $this->assertTrue($response['players'][1]['action_on']);
    }

    /**
     * @test
     * @return void
     */
    public function itCanResumeTheGame()
    {
        $this->isHeadsUp()
            ->setHand()
            ->setGamePlay();

        $this->gamePlay->start();
        
        $response = $this->sitControllerResponse();

        $this->assertTrue($response['players'][1]['action_on']);
    }
}
