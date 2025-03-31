<?php

namespace Atsmacode\Framework\Pdo;

use Atsmacode\Framework\FrameworkConfigProvider;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class PdoLiveFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): object
    {
        $configProvider = $container->get(FrameworkConfigProvider::class);
        $config = $configProvider->get();

        return new PdoConnection($config, 'live');
    }
}
