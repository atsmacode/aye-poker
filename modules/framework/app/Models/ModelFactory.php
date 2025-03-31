<?php

namespace Atsmacode\Framework\Models;

use Atsmacode\Framework\Database\ConnectionInterface;
use Atsmacode\Framework\Dbal\Model;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class ModelFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): Model
    {
        $connection = $container->get(ConnectionInterface::class);
        $logger = $container->get(LoggerInterface::class);

        return new $requestedName(
            $connection,
            $logger,
            new \ReflectionClass($requestedName)
        );
    }
}
