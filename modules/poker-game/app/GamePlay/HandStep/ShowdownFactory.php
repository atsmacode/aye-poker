<?php

namespace Atsmacode\PokerGame\GamePlay\HandStep;

use Atsmacode\PokerGame\PotHandler\PotHandler;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class ShowdownFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): object
    {
        return new Showdown($container->get(PotHandler::class));
    }
}
