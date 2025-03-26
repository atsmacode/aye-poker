<?php

namespace Atsmacode\PokerGame\Constants;

class Action
{
    const FOLD_ID  = 1;
    const CHECK_ID = 2;
    const CALL_ID  = 3;
    const BET_ID   = 4;
    const RAISE_ID = 5;

    const FOLD = [
        'id'   => self::FOLD_ID,
        'name' => 'Fold'
    ];

    const CHECK = [
        'id'   => self::CHECK_ID,
        'name' => 'Check'
    ];

    const CALL = [
        'id'   => self::CALL_ID,
        'name' => 'Call'
    ];

    const BET = [
        'id'   => self::BET_ID,
        'name' => 'Bet'
    ];

    const RAISE = [
        'id'   => self::RAISE_ID,
        'name' => 'Raise'
    ];

    const ALL = [
        self::FOLD,
        self::CHECK,
        self::CALL,
        self::BET,
        self::RAISE,
    ];
}
