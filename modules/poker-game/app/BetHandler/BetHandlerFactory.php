<?php

namespace Atsmacode\PokerGame\BetHandler;

use Atsmacode\PokerGame\BetHandler\BetHandler;
use Atsmacode\PokerGame\Models\PlayerActionLog;
use Atsmacode\PokerGame\Models\Stack;
use Atsmacode\PokerGame\Models\TableSeat;
use Atsmacode\PokerGame\PotHandler\PotHandler;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class BetHandlerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $potHandler           = $container->get(PotHandler::class);
        $playerActionLogModel = $container->get(PlayerActionLog::class);
        $stackModel           = $container->get(Stack::class);
        $tableSeatModel       = $container->get(TableSeat::class);

        return new BetHandler(
            $potHandler,
            $playerActionLogModel,
            $stackModel,
            $tableSeatModel
        );
    }
}
