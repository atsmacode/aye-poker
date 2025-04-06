<?php

namespace Atsmacode\PokerGame\GamePlay\GameStyle;

class PotLimitHoldEm implements GameStyle
{
    public array $streets;
    public string $limit;

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

    public function getLimit(): string
    {
        return 'pot';
    }
}
