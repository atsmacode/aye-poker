<?php

namespace Atsmacode\CardGames\Tests;

use Atsmacode\CardGames\CardGamesConfigProvider;
use Atsmacode\Framework\DatabaseProvider;
use PHPUnit\Framework\TestCase;

abstract class BaseTest extends TestCase
{
    protected function setUp(): void
    {
        $GLOBALS['THE_ROOT'] = '';
        $GLOBALS['dev']      = true;
        $config              = (new CardGamesConfigProvider)->get();
        $env                 = 'test';

        $GLOBALS['connection'] = DatabaseProvider::getConnection($config, $env);
    }
}
