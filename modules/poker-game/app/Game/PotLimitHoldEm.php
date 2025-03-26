<?php

namespace Atsmacode\PokerGame\Game;

class PotLimitHoldEm implements Game
{
    public array $streets;
    public string $limit;

    public function __construct()
    {
        $this->streets = [
            1 => [
                'name'            => 'Pre-flop',
                'whole_cards'     => 2,
                'community_cards' => 0
            ],
            2 => [
                'name'            => 'Flop',
                'whole_cards'     => 0,
                'community_cards' => 3
            ],
            3 => [
                'name'            => 'Turn',
                'whole_cards'     => 0,
                'community_cards' => 1
            ],
            4 => [
                'name'            => 'River',
                'whole_cards'     => 0,
                'community_cards' => 1
            ]
        ];

        $this->limit = 'pot';
    }
}
