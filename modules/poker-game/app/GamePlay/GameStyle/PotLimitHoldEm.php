<?php

namespace Atsmacode\PokerGame\GamePlay\GameStyle;

use Atsmacode\PokerGame\GamePlay\HandFlow\StartSteps\CreatePlayerActions;
use Atsmacode\PokerGame\GamePlay\HandFlow\StartSteps\DealerAndBlinds;
use Atsmacode\PokerGame\GamePlay\HandFlow\StartSteps\LoadStacks;

class PotLimitHoldEm implements GameStyle
{
    private array $streets;
    private string $limit;

    public function __construct()
    {
        $this->streets = [
            1 => [
                'name' => 'Pre-flop',
                'whole_cards' => 2,
                'community_cards' => 0,
            ],
            2 => [
                'name' => 'Flop',
                'whole_cards' => 0,
                'community_cards' => 3,
            ],
            3 => [
                'name' => 'Turn',
                'whole_cards' => 0,
                'community_cards' => 1,
            ],
            4 => [
                'name' => 'River',
                'whole_cards' => 0,
                'community_cards' => 1,
            ],
        ];

        $this->limit = 'pot';
    }

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
        return [
            CreatePlayerActions::class,
            LoadStacks::class,
            DealerAndBlinds::class,
        ];
    }
}
