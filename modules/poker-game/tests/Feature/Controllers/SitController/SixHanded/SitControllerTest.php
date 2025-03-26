<?php

namespace Atsmacode\PokerGame\Tests\Feature\Controllers\SitController\SixHanded;

use Atsmacode\PokerGame\Tests\BaseTest;
use Atsmacode\PokerGame\Tests\HasActionPosts;
use Atsmacode\PokerGame\Tests\HasGamePlay;

class SitControllerTest extends BaseTest
{
    use HasGamePlay, HasActionPosts;

    protected function setUp(): void
    {
        parent::setUp();

        $this->isSixHanded()
            ->setGamePlay();
    }

    /**
     * @test
     * @return void
     */
    public function the_pre_flop_action_will_initially_be_on_player_four()
    {
        $response = $this->sitControllerResponse();

        $this->assertTrue($response['players'][4]['action_on']);
    }

    /**
     * @test
     * @return void
     */
    public function if_there_are_two_seats_after_current_dealer_big_blind_will_be_seat_one()
    {
        $currentDealer = $this->tableSeatFour;

        $response = $this->sitControllerResponse($currentDealer);

        $this->assertEquals(1, $response['players'][6]['small_blind']);
        $this->assertEquals(1, $response['players'][1]['big_blind']);
    }

    /**
     * @test
     * @return void
     */
    public function if_there_is_one_seat_after_current_dealer_big_blind_will_be_seat_two()
    {
        $currentDealer = $this->tableSeatFive;

        $response = $this->sitControllerResponse($currentDealer);

        $this->assertEquals(1, $response['players'][1]['small_blind']);
        $this->assertEquals(1, $response['players'][2]['big_blind']);
    }
}
