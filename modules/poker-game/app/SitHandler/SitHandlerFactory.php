<?php

namespace Atsmacode\PokerGame\SitHandler;

use Atsmacode\PokerGame\Models\TableSeat;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class SitHandlerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): object
    {
        $tableSeatModel = $container->get(TableSeat::class);

        return new SitHandler(
            $tableSeatModel
        );
    }
}
