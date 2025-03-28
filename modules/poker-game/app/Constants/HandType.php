<?php

namespace Atsmacode\PokerGame\Constants;

class HandType
{
    public const ROYAL_FLUSH = [
        'id' => 1,
        'name' => 'Royal Flush',
        'ranking' => 1,
    ];

    public const STRAIGHT_FLUSH = [
        'id' => 2,
        'name' => 'Straight Flush',
        'ranking' => 2,
    ];

    public const QUADS = [
        'id' => 3,
        'name' => 'Four of a Kind',
        'ranking' => 3,
    ];

    public const FULL_HOUSE = [
        'id' => 4,
        'name' => 'Full House',
        'ranking' => 4,
    ];

    public const FLUSH = [
        'id' => 5,
        'name' => 'Flush',
        'ranking' => 5,
    ];

    public const STRAIGHT = [
        'id' => 6,
        'name' => 'Straight',
        'ranking' => 6,
    ];

    public const TRIPS = [
        'id' => 7,
        'name' => 'Three of a Kind',
        'ranking' => 7,
    ];

    public const TWO_PAIR = [
        'id' => 8,
        'name' => 'Two Pair',
        'ranking' => 8,
    ];

    public const PAIR = [
        'id' => 9,
        'name' => 'Pair',
        'ranking' => 9,
    ];

    public const HIGH_CARD = [
        'id' => 10,
        'name' => 'High Card',
        'ranking' => 10,
    ];

    public const ALL = [
        self::ROYAL_FLUSH,
        self::STRAIGHT_FLUSH,
        self::QUADS,
        self::FULL_HOUSE,
        self::FLUSH,
        self::STRAIGHT,
        self::TRIPS,
        self::TWO_PAIR,
        self::PAIR,
        self::HIGH_CARD,
    ];
}
