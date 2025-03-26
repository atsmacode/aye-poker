<?php

namespace Atsmacode\CardGames\Constants;

class Suit
{
    const CLUBS_SUIT_ID     = 1;
    const DIAMONDS_SUID_ID = 2;
    const HEARTS_SUIT_ID   = 3;
    const SPADES_SUIT_ID   = 4;

    const CLUBS = [
        'suit_id'          => self::CLUBS_SUIT_ID,
        'suit'             => 'Clubs',
        'suitAbbreviation' => 'C',
    ];

    const DIAMONDS = [
        'suit_id'          => self::DIAMONDS_SUID_ID,
        'suit'             => 'Diamonds',
        'suitAbbreviation' => 'D',
    ];

    const HEARTS = [
        'suit_id'          => self::HEARTS_SUIT_ID,
        'suit'             => 'Hearts',
        'suitAbbreviation' => 'H',
    ];

    const SPADES = [
        'suit_id'          => self::SPADES_SUIT_ID,
        'suit'             => 'Spades',
        'suitAbbreviation' => 'S',
    ];

    const ALL = [
        self::CLUBS,
        self::DIAMONDS,
        self::HEARTS,
        self::SPADES,
    ];
}
