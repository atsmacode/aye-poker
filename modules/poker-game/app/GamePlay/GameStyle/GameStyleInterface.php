<?php

namespace Atsmacode\PokerGame\GamePlay\GameStyle;

/**
 * Define the unique config of different poker styles.
 */
interface GameStyleInterface
{
    public function getStreets(): array;

    public function getStreet(int $id): array;

    public function getLimit(): string; // TODO: Limit Enum/Constant

    public function startSteps(): array;
}
