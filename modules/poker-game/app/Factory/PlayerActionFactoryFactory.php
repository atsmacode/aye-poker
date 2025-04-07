<?php

namespace Atsmacode\PokerGame\Factory;

use Atsmacode\PokerGame\Models\PlayerAction;
use Atsmacode\PokerGame\Models\PlayerActionLog;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class PlayerActionFactoryFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): object
    {
        return new PlayerActionFactory(
            $container->build(PlayerAction::class),
            $container->build(PlayerActionLog::class)
        );
    }
}
