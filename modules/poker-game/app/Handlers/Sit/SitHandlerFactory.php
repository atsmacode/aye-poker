<?php

namespace Atsmacode\PokerGame\Handlers\Sit;

use Atsmacode\PokerGame\Repository\TableSeat\TableSeatRepository;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class SitHandlerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): object
    {
        return new SitHandler($container->get(TableSeatRepository::class));
    }
}
