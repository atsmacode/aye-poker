<?php

namespace Atsmacode\CardGames\Factory;

/**
 * Factory class uing Card constants as a reference
 * to create test scenarios without calling models
 * and DB to generate Card objects/data.
 *
 * TODO: use Enums instead
 */
class CardFactory
{
    public static function create(array $cardConstant): array
    {
        $compiledConstant = array_merge(
            $cardConstant['rank'],
            $cardConstant['suit']
        );
        $compiledConstant['id'] = $cardConstant['id'];

        return $compiledConstant;
    }
}
