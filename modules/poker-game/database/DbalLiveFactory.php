<?php

namespace Atsmacode\PokerGame\Database;

use Atsmacode\Framework\Database\DbalConnection;
use Atsmacode\PokerGame\PokerGameConfigProvider;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class DbalLiveFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): object
    {
        $configProvider = $container->get(PokerGameConfigProvider::class);
        $config         = $configProvider->get();

        return new DbalConnection($config, 'live');
    }
}
