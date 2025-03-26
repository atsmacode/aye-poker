<?php

namespace Atsmacode\PokerGame\PlayerHandler;

use Atsmacode\PokerGame\Models\TableSeat;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class PlayerHandlerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $tableSeatModel = $container->get(TableSeat::class);

        return new PlayerHandler($tableSeatModel);
    }
}
