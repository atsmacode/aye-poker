<?php

namespace Atsmacode\PokerGame\Services;

use Atsmacode\PokerGame\ActionHandler\ActionHandler;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class GamePlayServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): object
    {
        $actionHandler = $container->get(ActionHandler::class);
        $controller = new $requestedName($container, $actionHandler);

        return $controller;
    }
}
