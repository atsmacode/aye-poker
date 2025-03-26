<?php

namespace Atsmacode\PokerGame\Constants;

class HandType
{
    const ROYAL_FLUSH = [
        'id'      => 1,
        'name'    => 'Royal Flush',
        'ranking' => 1
    ];

    const STRAIGHT_FLUSH = [
        'id'      => 2,
        'name'    => 'Straight Flush',
        'ranking' => 2
    ];

    const QUADS = [
        'id'      => 3,
        'name'    => 'Four of a Kind',
        'ranking' => 3
    ];

    const FULL_HOUSE = [
        'id'      => 4,
        'name'    => 'Full House',
        'ranking' => 4
    ];

    const FLUSH = [
        'id'      => 5,
        'name'    => 'Flush',
        'ranking' => 5
    ];

    const STRAIGHT = [
        'id'      => 6,
        'name'    => 'Straight',
        'ranking' => 6
    ];

    const TRIPS = [
        'id'      => 7,
        'name'    => 'Three of a Kind',
        'ranking' => 7
    ];

    const TWO_PAIR = [
        'id'      => 8,
        'name'    => 'Two Pair',
        'ranking' => 8
    ];

    const PAIR = [
        'id'      => 9,
        'name'    => 'Pair',
        'ranking' => 9
    ];


    const HIGH_CARD = [
        'id'      => 10,
        'name'    => 'High Card',
        'ranking' => 10
    ];

    const ALL = [
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