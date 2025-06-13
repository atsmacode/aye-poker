<?php

namespace Atsmacode\PokerGame\GamePlay\HandFlow\StartSteps;

use Atsmacode\PokerGame\Models\HandStreet;
use Atsmacode\PokerGame\Models\PlayerAction;
use Atsmacode\PokerGame\Models\Street;
use Atsmacode\PokerGame\Models\TableSeat;
use Atsmacode\PokerGame\Services\Blinds\BlindService;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class SetDealerAndBlindsFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): object
    {
        return new SetDealerAndBlinds(
            $container->build(Street::class),
            $container->build(HandStreet::class),
            $container->build(PlayerAction::class),
            $container->build(TableSeat::class),
            $container->get(BlindService::class)
        );
    }
}
