<?php

namespace Atsmacode\PokerGame\Services\Blinds;

use Atsmacode\PokerGame\Handlers\BetHandler\BetHandler;
use Atsmacode\PokerGame\Models\PlayerActionLog;
use Atsmacode\PokerGame\Models\TableSeat;
use Atsmacode\PokerGame\Services\Pots\PotService;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class BlindServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): object
    {
        return new BlindService(
            $container->get(BetHandler::class),
            $container->get(PotService::class),
            $container->get(PlayerActionLog::class),
            $container->get(TableSeat::class)
        );
    }
}
