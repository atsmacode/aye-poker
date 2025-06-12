<?php

namespace Atsmacode\PokerGame\GamePlay\HandFlow\StartSteps;

use Atsmacode\PokerGame\Models\HandStreet;
use Atsmacode\PokerGame\Models\PlayerAction;
use Atsmacode\PokerGame\Models\Street;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class CreatePlayerActionsFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): object
    {
        return new CreatePlayerActions(
            $container->build(Street::class),
            $container->build(HandStreet::class),
            $container->build(PlayerAction::class)
        );
    }
}
