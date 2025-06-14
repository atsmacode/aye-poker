<?php

namespace Atsmacode\PokerGame\Tests\Feature\Controllers\PlayerActionController;

use Atsmacode\PokerGame\Tests\BaseTest;
use Atsmacode\PokerGame\Tests\HasActionPosts;
use Atsmacode\PokerGame\Tests\HasHandFlow;

class PlayerActionControllerTest extends BaseTest
{
    use HasHandFlow;
    use HasActionPosts;

    protected function setUp(): void
    {
        parent::setUp();

        $this->isSixHanded()
            ->setHand()
            ->setHandFlow();
    }

    /**
     * @test
     *
     * @return void
     */
    public function itReturnsExpectedResponseKeys()
    {
        $this->handFlow->process($this->gameState);

        $request = $this->setPost();
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
            'sittingOut',
            'mode',
            'message',
        ];
    }
}
