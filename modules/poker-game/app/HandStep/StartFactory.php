<?php

namespace Atsmacode\PokerGame\HandStep;

use Atsmacode\PokerGame\BetHandler\BetHandler;
use Atsmacode\PokerGame\Models\HandStreet;
use Atsmacode\PokerGame\Models\PlayerAction;
use Atsmacode\PokerGame\Models\Stack;
use Atsmacode\PokerGame\Models\Street;
use Atsmacode\PokerGame\Models\TableSeat;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class StartFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $streetModel       = $container->get(Street::class);
        $handStreetModel   = $container->get(HandStreet::class);
        $playerActionModel = $container->get(PlayerAction::class);
        $stackModel        = $container->get(Stack::class);
        $tableSeatModel    = $container->get(TableSeat::class);
        $betHandler        = $container->get(BetHandler::class);

        return new Start(
            $container,
            $streetModel,
            $handStreetModel,
            $playerActionModel,
            $stackModel,
            $tableSeatModel,
            $betHandler
        );
    }
}
