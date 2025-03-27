<?php

namespace Atsmacode\PokerGame;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class PokerGameConfigProviderFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): object
    {
        return new PokerGameConfigProvider();
    }
}
