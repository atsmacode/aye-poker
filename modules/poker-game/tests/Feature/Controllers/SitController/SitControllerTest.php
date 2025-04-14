<?php

namespace Atsmacode\PokerGame\Tests\Feature\Controllers\SitController;

use Atsmacode\PokerGame\Tests\BaseTest;
use Atsmacode\PokerGame\Tests\HasActionPosts;
use Atsmacode\PokerGame\Tests\HasGamePlay;

class SitControllerTest extends BaseTest
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
    public function itReturnsValidResponseKeysOnPostRequest()
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
     *
     * @return void
     */
    public function withBlinds25And50ThePotSizeWillBe75OnceTheHandIsStarted()
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
            'sittingOut',
            'mode'
        ];
    }
}
