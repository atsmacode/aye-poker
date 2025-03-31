<?php

namespace Atsmacode\PokerGame\Controllers;

use Atsmacode\PokerGame\Services\JoinTable;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class SitControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): object
    {
        $controller = new $requestedName($container->get(JoinTable::class));

        return $controller;
    }
}
