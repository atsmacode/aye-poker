<?php

namespace Atsmacode\PokerGame\GamePlay\GameStyle;

use Atsmacode\PokerGame\GamePlay\HandFlow\StartSteps\CreatePlayerActions;
use Atsmacode\PokerGame\GamePlay\HandFlow\StartSteps\DealCards;
use Atsmacode\PokerGame\GamePlay\HandFlow\StartSteps\SetDealerAndBlinds;
use Atsmacode\PokerGame\GamePlay\HandFlow\StartSteps\LoadStacks;

class PotLimitOmaha implements GameStyle
{
    private array $streets;
    private string $limit;

    public function __construct()
    {
        $this->streets = [
            1 => [
                'name' => 'Pre-flop',
                'whole_cards' => 5,
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

    public function getLimit(): string
    {
        return $this->limit;
    }

    public function startSteps(): array
    {
        return [
            CreatePlayerActions::class,
            LoadStacks::class,
            SetDealerAndBlinds::class,
            DealCards::class,
        ];
    }
}
