<?php

namespace Atsmacode\PokerGame\Tests\Unit\Services\Games;

use Atsmacode\PokerGame\Enums\GameMode;
use Atsmacode\PokerGame\Models\Game;
use Atsmacode\PokerGame\Services\Games\GameService;
use Atsmacode\PokerGame\Tests\BaseTest;
use Atsmacode\PokerGame\Tests\HasRequests;

class GameServiceTest extends BaseTest
{
    use HasRequests;

    private GameService $gameService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->gameService = $this->container->get(GameService::class);
    }

    /**
     * @dataProvider playerCount
     */
    public function testItCanSetupATestGame($playerCount)
    {
        $request = $this->createRequest(['mode' => GameMode::TEST->value, 'player_count' => $playerCount]);
        $game = $this->gameService->create($request);
        $table = $game->getTable();

        $this->assertInstanceOf(Game::class, $game);

        $seats = $table->getSeats();

        $playerAssertions = 0;

        while ($playerAssertions < $playerCount) {
            $this->assertContains($playerAssertions + 1, array_column($seats, 'player_id'));

            ++$playerAssertions;
        }
    }

    public function playerCount(): array
    {
        return [
            '1 players' => [1],
            '2 players' => [2],
            '3 players' => [3],
            '4 players' => [4],
            '5 players' => [5],
            '6 players' => [6],
        ];
    }
}
