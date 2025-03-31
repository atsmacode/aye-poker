<?php

namespace Atsmacode\PokerGame\PlayerHandler;

use Atsmacode\PokerGame\Models\TableSeat;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class PlayerHandlerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): object
    {
        return new PlayerHandler($container->get(TableSeat::class));
    }
}
