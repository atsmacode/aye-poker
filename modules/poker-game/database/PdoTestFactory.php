<?php

namespace Atsmacode\PokerGame\Database;

use Atsmacode\Framework\Pdo\PdoConnection;
use Atsmacode\PokerGame\PokerGameConfigProvider;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class PdoTestFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $configProvider = $container->get(PokerGameConfigProvider::class);
        $config         = $configProvider->get();

        return new PdoConnection($config, 'test');
    }
}
