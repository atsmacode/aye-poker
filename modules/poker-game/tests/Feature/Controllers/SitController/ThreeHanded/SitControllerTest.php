<?php

namespace Atsmacode\PokerGame\Tests\Feature\Controllers\SitController\ThreeHanded;

use Atsmacode\PokerGame\Tests\BaseTest;
use Atsmacode\PokerGame\Tests\HasActionPosts;
use Atsmacode\PokerGame\Tests\HasGamePlay;
use Atsmacode\PokerGame\Tests\HasStreets;

class SitControllerTest extends BaseTest
{
    use HasGamePlay, HasActionPosts;

    protected function setUp(): void
    {
        parent::setUp();

        $this->isThreeHanded()
            ->setGamePlay();
    }

    /**
     * @test
     * @return void
     */
    public function itCanStartTheGame()
    {
        $response = $this->sitControllerResponse();

        // The small blind was posted
        $this->assertEquals(25, $response['players'][2]['bet_amount']);
        $this->assertEquals('Bet', $response['players'][2]['action_name']);

        // The big blind was posted
        $this->assertEquals(50, $response['players'][3]['bet_amount']);
        $this->assertEquals('Bet', $response['players'][3]['action_name']);

        // The dealer, seat 1, has not acted yet
        $this->assertEquals(null, $response['players'][1]['bet_amount']);
        $this->assertEquals(null, $response['players'][1]['action_id']);

        // Each player in the hand has 2 whole cards
        foreach($response['players'] as $player){
            $this->assertCount(2, $player['whole_cards']);
        }
    }

    /**
     * @test
     * @return void
     */
    public function thePreFlopActionWillInitiallyBeOnPlayerOne()
    {
        $response = $this->sitControllerResponse();

        $this->assertTrue($response['players'][1]['action_on']);
    }
}
