<?php

namespace Atsmacode\PokerGame\Constants;

class Action
{
    public const FOLD_ID = 1;
    public const CHECK_ID = 2;
    public const CALL_ID = 3;
    public const BET_ID = 4;
    public const RAISE_ID = 5;

    public const FOLD = [
        'id' => self::FOLD_ID,
        'name' => 'Fold',
    ];

    public const CHECK = [
        'id' => self::CHECK_ID,
        'name' => 'Check',
    ];

    public const CALL = [
        'id' => self::CALL_ID,
        'name' => 'Call',
    ];

    public const BET = [
        'id' => self::BET_ID,
        'name' => 'Bet',
    ];

    public const RAISE = [
        'id' => self::RAISE_ID,
        'name' => 'Raise',
    ];

    public const ALL = [
        self::FOLD,
        self::CHECK,
        self::CALL,
        self::BET,
        self::RAISE,
    ];
}
