<?php

namespace Atsmacode\PokerGame\Repository\Game;

use Atsmacode\PokerGame\Models\Hand;
use Atsmacode\PokerGame\Models\Player;
use Atsmacode\PokerGame\Models\PlayerAction;
use Atsmacode\PokerGame\Models\Table;
use Atsmacode\PokerGame\Models\TableSeat;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class GameRepositoryFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): object
    {
        return new GameRepository(
            $container->get(Hand::class),
            $container->get(Table::class),
            $container->get(Player::class),
            $container->get(TableSeat::class),
            $container->get(PlayerAction::class)
        );
    }
}
