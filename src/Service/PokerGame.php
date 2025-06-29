<?php

namespace App\Service;

use Atsmacode\PokerGame\PokerGameConfigProvider;
use Laminas\ServiceManager\ServiceManager;

class PokerGame
{
    public function get(string $class, ?string $rootDir = null): object
    {
        $serviceManager = $this->getServiceManager($rootDir);

        return $serviceManager->get($class);
    }

    private function getServiceManager(?string $rootDir = null): ServiceManager
    {
        $rootDir = $rootDir ?? '../';
        $provider = (new PokerGameConfigProvider($rootDir));
        $pokerGameConfig        = $provider->get();
        $pokerGameDependencyMap = $pokerGameConfig['dependencies'];

        $serviceManager = new ServiceManager($pokerGameDependencyMap);

        $serviceManager->setFactory(PokerGameConfigProvider::class, fn() => $provider);

        return $serviceManager;
    }
}