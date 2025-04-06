<?php

namespace Atsmacode\PokerGame\Repository\Hand;

use Atsmacode\Framework\Database\ConnectionInterface;
use Atsmacode\PokerGame\Models\Hand;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class HandRepositoryFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): object
    {
        return new HandRepository(
            $container->get(ConnectionInterface::class),
            $container->get(LoggerInterface::class),
            $container->get(Hand::class)
        );
    }
}
