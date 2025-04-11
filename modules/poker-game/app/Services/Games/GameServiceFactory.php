<?php

namespace Atsmacode\PokerGame\Services\Games;

use Atsmacode\PokerGame\Models\Game;
use Atsmacode\PokerGame\Models\Player;
use Atsmacode\PokerGame\Models\Table;
use Atsmacode\PokerGame\Models\TableSeat;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class GameServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): object
    {
        return new GameService(
            $container->get(Table::class),
            $container->get(TableSeat::class),
            $container->get(Player::class),
            $container->get(Game::class)
        );
    }
}
