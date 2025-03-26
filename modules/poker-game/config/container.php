<?php

use Atsmacode\PokerGame\Controllers\PotLimitHoldEm\SitController;
use Atsmacode\PokerGame\PokerGameConfigProvider;
use Atsmacode\PokerGame\PokerGameRelConfigProviderFactory;
use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use Laminas\ServiceManager\ServiceManager;

$config                 = (new PokerGameConfigProvider('../'))->get();
$pokerGameDependencyMap = $config['dependencies'];
$serviceManager         = new ServiceManager($pokerGameDependencyMap);

$serviceManager->setFactory(PokerGameConfigProvider::class, new PokerGameRelConfigProviderFactory());
$serviceManager->addAbstractFactory(new ConfigAbstractFactory());

//var_dump($serviceManager);

/**
 * Example use code to test in browser.
 */

$response = $serviceManager->get(SitController::class)->sit(playerId: 1)->getContent();

var_dump(json_decode($response));
