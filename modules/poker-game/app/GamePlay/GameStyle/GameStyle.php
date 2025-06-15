<?php

namespace Atsmacode\PokerGame\GamePlay\GameStyle;

abstract class GameStyle implements GameStyleInterface
{
    protected array $streets;
    protected string $limit;
    protected array $startSteps;

    public function getStreets(): array
    {
        return $this->streets;
    }

    public function getStreet(int $id): array
    {
        return $this->streets[$id];
    }

    public function getLimit(): string
    {
        return $this->limit;
    }

    public function startSteps(): array
    {
        return $this->startSteps;
    }
}