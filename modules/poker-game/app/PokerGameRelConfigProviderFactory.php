<?php

namespace Atsmacode\PokerGame;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class PokerGameRelConfigProviderFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        return new PokerGameConfigProvider('../');
    }
}
