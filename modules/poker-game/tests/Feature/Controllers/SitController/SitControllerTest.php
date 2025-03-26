<?php

namespace Atsmacode\PokerGame\Tests\Feature\Controllers\SitController;

use Atsmacode\PokerGame\Controllers\PotLimitHoldEm\SitController as PotLimitHoldEmSitController;
use Atsmacode\PokerGame\Tests\BaseTest;
use Atsmacode\PokerGame\Tests\HasActionPosts;
use Atsmacode\PokerGame\Tests\HasGamePlay;

class SitControllerTest extends BaseTest
{
    use HasGamePlay, HasActionPosts;

    protected function setUp(): void
    {
        parent::setUp();

        $this->isSixHanded()->setGamePlay();
    }

    /**
     * @test
     * @return void
     */
    public function it_returns_valid_response_keys_on_post_request()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';

        $response = $this->sitControllerResponse();

        $this->assertEquals(
            $this->validResponseKeys(),
            array_keys($response)
        );
    }

    /**
     * @test
     * @return void
     */
    public function with_blinds_25_and_50_the_pot_size_will_be_75_once_the_hand_is_started()
    {
        $response = $this->sitControllerResponse();

        $this->assertEquals(75, $response['pot']);
    }

    public function validResponseKeys()
    {
        return [
            'pot',
            'communityCards',
            'players',
            'winner',
            'sittingOut'
        ];
    }
}
