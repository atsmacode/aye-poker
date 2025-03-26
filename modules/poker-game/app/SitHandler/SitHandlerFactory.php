<?php

namespace Atsmacode\PokerGame\SitHandler;

use Atsmacode\PokerGame\GameState\GameState;
use Atsmacode\PokerGame\Models\Table;
use Atsmacode\PokerGame\Models\TableSeat;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class SitHandlerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $gameState      = $container->get(GameState::class);
        $tableModel     = $container->get(Table::class);
        $tableSeatModel = $container->get(TableSeat::class);

        return new SitHandler(
            $gameState,
            $tableModel,
            $tableSeatModel
        );
    }
}
