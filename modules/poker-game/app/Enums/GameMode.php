<?php

namespace Atsmacode\PokerGame\Enums;

enum GameMode: int
{
    case TEST = 1;
    case REAL = 2;

    public function display(): string
    {
        return match ($this) {
            self::TEST => 'Test',
            self::REAL => 'Real',
        };
    }
}
