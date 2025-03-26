<?php

namespace Atsmacode\PokerGame\GamePlay;

use Atsmacode\PokerGame\Dealer\PokerDealer;
use Atsmacode\PokerGame\GameData\GameData;
use Atsmacode\PokerGame\GameState\GameState;
use Atsmacode\PokerGame\HandStep\NewStreet;
use Atsmacode\PokerGame\HandStep\Showdown;
use Atsmacode\PokerGame\HandStep\Start;
use Atsmacode\PokerGame\Models\TableSeat;
use Atsmacode\PokerGame\PlayerHandler\PlayerHandler;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class GamePlayFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $gameState = isset($options['gameState']) ? $options['gameState'] : new GameState(
            $container->get(GameData::class),
            $container->get(PokerDealer::class),
            isset($options['hand']) ? $options['hand'] : null
        );

        return new GamePlay(
            $gameState,
            $options['game'],
            $container->get(Start::class),
            $container->get(NewStreet::class),
            $container->get(Showdown::class),
            $container->get(PlayerHandler::class),
            $container->get(TableSeat::class)
        );
    }
}
