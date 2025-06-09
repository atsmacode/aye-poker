<?php

namespace Atsmacode\PokerGame\Services\Sit;

use Atsmacode\PokerGame\Handlers\Sit\SitHandler;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class SitServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): object
    {
        return new SitService($container, $container->get(SitHandler::class));
    }
}
