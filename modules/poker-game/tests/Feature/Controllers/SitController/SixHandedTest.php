<?php

namespace Atsmacode\PokerGame\Tests\Feature\Controllers\SitController;

use Atsmacode\PokerGame\Tests\BaseTest;
use Atsmacode\PokerGame\Tests\HasActionPosts;
use Atsmacode\PokerGame\Tests\HasGamePlay;

class SixHandedTest extends BaseTest
{
    use HasGamePlay;
    use HasActionPosts;

    protected function setUp(): void
    {
        parent::setUp();

        $this->isSixHanded()
            ->setGame()
            ->setGamePlay();
    }

    /**
     * @test
     *
     * @return void
     */
    public function thePreFlopActionWillInitiallyBeOnPlayerFour()
    {
        $response = $this->sitControllerResponse();

        $this->assertTrue($response['players'][4]['action_on']);
    }

    /**
     * @test
     *
     * @return void
     */
    public function ifThereAreTwoSeatsAfterCurrentDealerBigBlindWillBeSeatOne()
    {
        $currentDealer = $this->tableSeatFour;

        $response = $this->sitControllerResponse($currentDealer);

        $this->assertEquals(1, $response['players'][6]['small_blind']);
        $this->assertEquals(1, $response['players'][1]['big_blind']);
    }

    /**
     * @test
     *
     * @return void
     */
    public function ifThereIsOneSeatAfterCurrentDealerBigBlindWillBeSeatTwo()
    {
        $currentDealer = $this->tableSeatFive;

        $response = $this->sitControllerResponse($currentDealer);

        $this->assertEquals(1, $response['players'][1]['small_blind']);
        $this->assertEquals(1, $response['players'][2]['big_blind']);
    }
}
