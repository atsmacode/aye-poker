<?php

namespace Atsmacode\PokerGame\BetHandler;

use Atsmacode\PokerGame\Models\PlayerActionLog;
use Atsmacode\PokerGame\Models\Stack;
use Atsmacode\PokerGame\Models\TableSeat;
use Atsmacode\PokerGame\PotHandler\PotHandler;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class BetHandlerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): object
    {
        return new BetHandler(
            $container->get(PotHandler::class),
            $container->get(PlayerActionLog::class),
            $container->get(Stack::class),
            $container->get(TableSeat::class)
        );
    }
}
