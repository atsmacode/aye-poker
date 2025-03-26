<?php

namespace Atsmacode\CardGames;

use Atsmacode\Framework\Pdo\PdoConnection;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class PdoTestFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $configProvider = $container->get(CardGamesConfigProvider::class);
        $config         = $configProvider->get();

        return new PdoConnection($config, 'test');
    }
}
