<?php

namespace Atsmacode\PokerGame\Tests\Feature\Controllers\PlayerActionController;

use Atsmacode\PokerGame\Tests\BaseTest;
use Atsmacode\PokerGame\Tests\HasActionPosts;
use Atsmacode\PokerGame\Tests\HasGamePlay;

class PlayerActionControllerTest extends BaseTest
{
    use HasGamePlay, HasActionPosts;

    protected function setUp(): void
    {
        parent::setUp();

        $this->isSixHanded()
            ->setHand()
            ->setGamePlay();
        
    }

    /**
     * @test
     * @return void
     */
    public function it_returns_expected_response_keys()
    {
        $this->gamePlay->start();

        $request  = $this->setPost();
        $response = $this->actionControllerResponse($request);

        $this->assertEquals(
            $this->validResponseKeys(),
            array_keys($response)
        );
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
