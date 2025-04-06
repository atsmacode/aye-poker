<?php

namespace Atsmacode\PokerGame\Repository\Table;

use Atsmacode\Framework\Database\ConnectionInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class TableRepositoryFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): object
    {
        return new TableRepository(
            $container->get(ConnectionInterface::class),
            $container->get(LoggerInterface::class)
        );
    }
}
