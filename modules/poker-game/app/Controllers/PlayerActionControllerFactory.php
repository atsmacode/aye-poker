<?php

namespace Atsmacode\PokerGame\Controllers;

use Atsmacode\PokerGame\ActionHandler\ActionHandler;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class PlayerActionControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $actionHandler = $container->get(ActionHandler::class);
        $controller    = new $requestedName($container, $actionHandler);

        return $controller;
    }
}
