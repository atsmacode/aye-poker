<?php

namespace Atsmacode\PokerGame\Tests\Feature\Controllers\SitController;

use Atsmacode\PokerGame\Tests\BaseTest;
use Atsmacode\PokerGame\Tests\HasActionPosts;
use Atsmacode\PokerGame\Tests\HasHandFlow;
use Atsmacode\PokerGame\Tests\HasStreets;

class ThreeHandedActionOptionsTest extends BaseTest
{
    use HasHandFlow;
    use HasActionPosts;
    use HasStreets;

    protected function setUp(): void
    {
        parent::setUp();

        $this->isThreeHanded()
            ->setHand();
    }

    /**
     * @test
     *
     * @return void
     */
    public function theSmallBlindWillBeFirstToActOnTheFlop()
    {
        $this->setHandFlow();

        $this->handFlow->process($this->gameState);

        $this->givenActionsMeanNewStreetIsDealt();

        $this->setFlop();

        $response = $this->sitControllerResponse();

        $this->assertEquals(true, $response['players'][2]['action_on']);
    }

    /**
     * @test
     *
     * @return void
     */
    public function whenDealerIsSeatThreeSmallBlindWillBeFirstToActOnTheFlop()
    {
        $this->givenCurrentDealerIs($this->playerTwo->getId())
            ->setHandFlow();

        $this->handFlow->process($this->gameState);

        $this->givenActionsMeanNewStreetIsDealt();

        $this->setFlop();

        $response = $this->sitControllerResponse();

        $this->assertEquals(true, $response['players'][1]['action_on']);
    }

    private function givenActionsMeanNewStreetIsDealt()
    {
        $this->givenPlayerThreePreviouslyCalled();
        $this->givenPlayerThreeCanNotContinue();

        $this->givenPlayerOnePreviouslyCalled();
        $this->givenPlayerOneCanNotContinue();

        $this->givenPlayerTwoPreviouslyChecked();
        $this->givenPlayerTwoCanNotContinue();
    }
}
