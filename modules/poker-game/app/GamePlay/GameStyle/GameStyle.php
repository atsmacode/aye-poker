<?php

namespace Atsmacode\PokerGame\GamePlay\GameStyle;

/**
 * Define the unique config of different poker styles.
 */
interface GameStyle
{
    public function getStreets(): array;

    public function getLimit(): string; // TODO: Limit Enum/Constant

    public function startSteps(): array;
}
