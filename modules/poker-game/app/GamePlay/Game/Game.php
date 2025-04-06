<?php

namespace Atsmacode\PokerGame\GamePlay\Game;

/**
 * Define the unique config of different poker styles.
 * 
 * TODO: Maybe this belongs in a GameStyle or GameFormat namespace.
 * Which could also contain the blind format: No Limit / Pot Limit.
 */
interface Game
{
    public function getStreets(): array;
}
