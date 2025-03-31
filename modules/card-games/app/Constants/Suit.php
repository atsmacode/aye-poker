<?php

namespace Atsmacode\CardGames\Constants;

class Suit
{
    public const CLUBS_SUIT_ID = 1;
    public const DIAMONDS_SUID_ID = 2;
    public const HEARTS_SUIT_ID = 3;
    public const SPADES_SUIT_ID = 4;

    public const CLUBS = [
        'suit_id' => self::CLUBS_SUIT_ID,
        'suit' => 'Clubs',
        'suitAbbreviation' => 'C',
    ];

    public const DIAMONDS = [
        'suit_id' => self::DIAMONDS_SUID_ID,
        'suit' => 'Diamonds',
        'suitAbbreviation' => 'D',
    ];

    public const HEARTS = [
        'suit_id' => self::HEARTS_SUIT_ID,
        'suit' => 'Hearts',
        'suitAbbreviation' => 'H',
    ];

    public const SPADES = [
        'suit_id' => self::SPADES_SUIT_ID,
        'suit' => 'Spades',
        'suitAbbreviation' => 'S',
    ];

    public const ALL = [
        self::CLUBS,
        self::DIAMONDS,
        self::HEARTS,
        self::SPADES,
    ];
}
