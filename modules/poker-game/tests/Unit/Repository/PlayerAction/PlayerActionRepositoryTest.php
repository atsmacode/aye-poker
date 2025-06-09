<?php

namespace Atsmacode\PokerGame\Tests\Unit\Repository\PlayerAction;

use Atsmacode\PokerGame\Repository\PlayerAction\PlayerActionRepository;
use Atsmacode\PokerGame\Tests\BaseTest;
use Atsmacode\PokerGame\Tests\HasActionPosts;
use Atsmacode\PokerGame\Tests\HasHandFlow;

class PlayerActionRepositoryTest extends BaseTest
{
    use HasHandFlow;
    use HasActionPosts;

    private PlayerActionRepository $playerActionRepo;

    protected function setUp(): void
    {
        parent::setUp();

        $this->isThreeHanded()
            ->setHand()
            ->setHandFlow();

        $this->playerActionRepo = $this->container->build(PlayerActionRepository::class);
    }

    /**
     * @test
     *
     * @return void
     */
    public function itCanGetTheLatestActionOnANewHand()
    {
        $this->handFlow->process($this->gameState);
        $this->gameState->setBigBlind();

        $bigBlind = $this->gameState->getBigBlind();
        $latestAction = $this->playerActionRepo->getLatestAction($this->testHand->getId());

        $this->assertEquals($bigBlind['table_seat_id'], $latestAction->getTableSeatId());
    }
}
