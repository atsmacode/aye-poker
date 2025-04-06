<?php

namespace Atsmacode\PokerGame\GamePlay;

use Atsmacode\PokerGame\GamePlay\Dealer\PokerDealer;
use Atsmacode\PokerGame\GameData\GameData;
use Atsmacode\PokerGame\State\GameState\GameState;
use Atsmacode\PokerGame\GamePlay\HandStep\NewStreet;
use Atsmacode\PokerGame\GamePlay\HandStep\Showdown;
use Atsmacode\PokerGame\GamePlay\HandStep\Start;
use Atsmacode\PokerGame\Models\TableSeat;
use Atsmacode\PokerGame\PlayerHandler\PlayerHandler;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class GamePlayFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): object
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
