<?php

namespace Atsmacode\PokerGame\GamePlay\Game;

/**
 * Define the unique config of different poker styles.
 */
interface Game
{
    public function getStreets(): array;
}
