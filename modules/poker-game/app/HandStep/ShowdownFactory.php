<?php

namespace Atsmacode\PokerGame\HandStep;

use Atsmacode\PokerGame\PotHandler\PotHandler;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class ShowdownFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $potHandler = $container->get(PotHandler::class);

        return new Showdown($potHandler);
    }
}
