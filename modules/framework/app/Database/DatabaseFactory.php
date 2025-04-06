<?php

namespace Atsmacode\Framework\Database;

use Atsmacode\Framework\Database\ConnectionInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class DatabaseFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): Database
    {
        $connection = $container->get(ConnectionInterface::class);
        $logger = $container->get(LoggerInterface::class);

        return new $requestedName(
            $connection,
            $logger,
            $container
        );
    }
}
