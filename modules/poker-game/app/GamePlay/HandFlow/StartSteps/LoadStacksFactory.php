<?php

namespace Atsmacode\PokerGame\GamePlay\HandFlow\StartSteps;

use Atsmacode\PokerGame\Models\Stack;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class LoadStacksFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): object
    {
        return new LoadStacks($container->build(Stack::class));
    }
}
