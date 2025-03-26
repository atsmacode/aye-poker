<?php

namespace Atsmacode\CardGames\Constants;

class Rank
{
    const ACE_RANK_ID   = 1;
    const DEUCE_RANK_ID = 2;
    const THREE_RANK_ID = 3;
    const FOUR_RANK_ID  = 4;
    const FIVE_RANK_ID  = 5;
    const SIX_RANK_ID   = 6;
    const SEVEN_RANK_ID = 7;
    const EIGHT_RANK_ID = 8;
    const NINE_RANK_ID  = 9;
    const TEN_RANK_ID   = 10;
    const JACK_RANK_ID  = 11;
    const QUEEN_RANK_ID = 12;
    const KING_RANK_ID  = 13;

    const ACE_HIGH_RANK_ID = 14;

    const ACE = [
        'rank_id'           => self::ACE_RANK_ID,
        'rank'              => 'Ace',
        'ranking'           => 1,
        'rankAbbreviation'  => 'A',
    ];

    const DEUCE = [
        'rank_id'           => self::DEUCE_RANK_ID,
        'rank'              => 'Deuce',
        'ranking'           => 2,
        'rankAbbreviation'  => '2',
    ];

    const THREE = [
        'rank_id'          => self::THREE_RANK_ID,
        'rank'             => 'Three',
        'ranking'          => 3,
        'rankAbbreviation' => '3',
    ];

    const FOUR = [
        'rank_id'          => self::FOUR_RANK_ID,
        'rank'             => 'Four',
        'ranking'          => 4,
        'rankAbbreviation' => '4',
    ];

    const FIVE = [
        'rank_id'          => self::FIVE_RANK_ID,
        'rank'             => 'Five',
        'ranking'          => 5,
        'rankAbbreviation' => '5',
    ];

    const SIX = [
        'rank_id'          => self::SIX_RANK_ID,
        'rank'             => 'Six',
        'ranking'          => 6,
        'rankAbbreviation' => '6',
    ];

    const SEVEN = [
        'rank_id'          => self::SEVEN_RANK_ID,
        'rank'             => 'Seven',
        'ranking'          => 7,
        'rankAbbreviation' => '7',
    ];

    const EIGHT = [
        'rank_id'          => self::EIGHT_RANK_ID,
        'rank'             => 'Eight',
        'ranking'          => 8,
        'rankAbbreviation' => '8',
    ];

    const NINE = [
        'rank_id'          => self::NINE_RANK_ID,
        'rank'             => 'Nine',
        'ranking'          => 9,
        'rankAbbreviation' => '9',
    ];

    const TEN = [
        'rank_id'          => self::TEN_RANK_ID,
        'rank'             => 'Ten',
        'ranking'          => 10,
        'rankAbbreviation' => '10',
    ];

    const JACK = [
        'rank_id'          => self::JACK_RANK_ID,
        'rank'             => 'Jack',
        'ranking'          => 11,
        'rankAbbreviation' => 'J',
    ];

    const QUEEN = [
        'rank_id'          => self::QUEEN_RANK_ID,
        'rank'             => 'Queen',
        'ranking'          => 12,
        'rankAbbreviation' => 'Q',
    ];

    const KING = [
        'rank_id'          => self::KING_RANK_ID,
        'rank'             => 'King',
        'ranking'          => 13,
        'rankAbbreviation' => 'K',
    ];

    const ALL = [
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
