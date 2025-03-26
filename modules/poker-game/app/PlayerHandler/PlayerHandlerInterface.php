<?php declare(strict_types=1);

namespace Atsmacode\PokerGame\PlayerHandler;

use Atsmacode\PokerGame\GameState\GameState;

interface PlayerHandlerInterface
{
    public function handle(GameState $gameState): array;
}
