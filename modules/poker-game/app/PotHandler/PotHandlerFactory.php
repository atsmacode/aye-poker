<?php

namespace Atsmacode\PokerGame\PotHandler;

use Atsmacode\PokerGame\Models\Pot;
use Atsmacode\PokerGame\Models\Stack;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class PotHandlerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): object
    {
        return new PotHandler(
            $container->get(Stack::class),
            $container->get(Pot::class)
        );
    }
}
