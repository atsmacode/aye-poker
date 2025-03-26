<?php

namespace Atsmacode\PokerGame\HandStep;

use Atsmacode\PokerGame\Models\HandStreet;
use Atsmacode\PokerGame\Models\PlayerAction;
use Atsmacode\PokerGame\Models\Street;
use Atsmacode\PokerGame\Models\TableSeat;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class NewStreetFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $streetModel       = $container->get(Street::class);
        $tableSeatModel    = $container->get(TableSeat::class);
        $handStreetModel   = $container->get(HandStreet::class);
        $playerActionModel = $container->get(PlayerAction::class);

        return new NewStreet(
            $streetModel,
            $tableSeatModel,
            $handStreetModel,
            $playerActionModel
        );
    }
}
