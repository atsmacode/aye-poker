<?php

namespace App\Service;

use Atsmacode\PokerGame\PokerGameConfigProvider;
use Laminas\ServiceManager\ServiceManager;

class PokerGame
{
    public function getServiceManager(): ServiceManager
    {
        $pokerGameConfig        = (new PokerGameConfigProvider('../'))->get();
        $pokerGameDependencyMap = $pokerGameConfig['dependencies'];

        $serviceManager = new ServiceManager($pokerGameDependencyMap);

        $serviceManager->setFactory(PokerGameConfigProvider::class, fn() => new PokerGameConfigProvider('../'));

        return $serviceManager;
    }
}