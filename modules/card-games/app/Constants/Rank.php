<?php

namespace Atsmacode\CardGames\Constants;

class Rank
{
    public const ACE_RANK_ID = 1;
    public const DEUCE_RANK_ID = 2;
    public const THREE_RANK_ID = 3;
    public const FOUR_RANK_ID = 4;
    public const FIVE_RANK_ID = 5;
    public const SIX_RANK_ID = 6;
    public const SEVEN_RANK_ID = 7;
    public const EIGHT_RANK_ID = 8;
    public const NINE_RANK_ID = 9;
    public const TEN_RANK_ID = 10;
    public const JACK_RANK_ID = 11;
    public const QUEEN_RANK_ID = 12;
    public const KING_RANK_ID = 13;

    public const ACE_HIGH_RANK_ID = 14;

    public const ACE = [
        'rank_id' => self::ACE_RANK_ID,
        'rank' => 'Ace',
        'ranking' => 1,
        'rankAbbreviation' => 'A',
    ];

    public const DEUCE = [
        'rank_id' => self::DEUCE_RANK_ID,
        'rank' => 'Deuce',
        'ranking' => 2,
        'rankAbbreviation' => '2',
    ];

    public const THREE = [
        'rank_id' => self::THREE_RANK_ID,
        'rank' => 'Three',
        'ranking' => 3,
        'rankAbbreviation' => '3',
    ];

    public const FOUR = [
        'rank_id' => self::FOUR_RANK_ID,
        'rank' => 'Four',
        'ranking' => 4,
        'rankAbbreviation' => '4',
    ];

    public const FIVE = [
        'rank_id' => self::FIVE_RANK_ID,
        'rank' => 'Five',
        'ranking' => 5,
        'rankAbbreviation' => '5',
    ];

    public const SIX = [
        'rank_id' => self::SIX_RANK_ID,
        'rank' => 'Six',
        'ranking' => 6,
        'rankAbbreviation' => '6',
    ];

    public const SEVEN = [
        'rank_id' => self::SEVEN_RANK_ID,
        'rank' => 'Seven',
        'ranking' => 7,
        'rankAbbreviation' => '7',
    ];

    public const EIGHT = [
        'rank_id' => self::EIGHT_RANK_ID,
        'rank' => 'Eight',
        'ranking' => 8,
        'rankAbbreviation' => '8',
    ];

    public const NINE = [
        'rank_id' => self::NINE_RANK_ID,
        'rank' => 'Nine',
        'ranking' => 9,
        'rankAbbreviation' => '9',
    ];

    public const TEN = [
        'rank_id' => self::TEN_RANK_ID,
        'rank' => 'Ten',
        'ranking' => 10,
        'rankAbbreviation' => '10',
    ];

    public const JACK = [
        'rank_id' => self::JACK_RANK_ID,
        'rank' => 'Jack',
        'ranking' => 11,
        'rankAbbreviation' => 'J',
    ];

    public const QUEEN = [
        'rank_id' => self::QUEEN_RANK_ID,
        'rank' => 'Queen',
        'ranking' => 12,
        'rankAbbreviation' => 'Q',
    ];

    public const KING = [
        'rank_id' => self::KING_RANK_ID,
        'rank' => 'King',
        'ranking' => 13,
        'rankAbbreviation' => 'K',
    ];

    public const ALL = [
        self::ACE,
        self::DEUCE,
        self::THREE,
        self::FOUR,
        self::FIVE,
        self::SIX,
        self::SEVEN,
        self::EIGHT,
        self::NINE,
        self::TEN,
        self::JACK,
        self::QUEEN,
        self::KING,
    ];
}
