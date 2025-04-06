<?php

namespace Atsmacode\PokerGame\Handlers\SitHandler;

use Atsmacode\PokerGame\Models\TableSeat;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class SitHandlerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): object
    {
        return new SitHandler($container->get(TableSeat::class));
    }
}
