<?php

namespace Atsmacode\PokerGame\Pipelines;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class GameStatePipelineFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): object
    {
        return new GameStatePipeline($container);
    }
}
