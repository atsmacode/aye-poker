<?php

namespace Atsmacode\PokerGame\GamePlay\HandStep;

use Atsmacode\PokerGame\Models\HandStreet;
use Atsmacode\PokerGame\Models\PlayerAction;
use Atsmacode\PokerGame\Models\Stack;
use Atsmacode\PokerGame\Models\Street;
use Atsmacode\PokerGame\Models\TableSeat;
use Atsmacode\PokerGame\Services\BlindService\BlindService;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class StartFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): object
    {
        return new Start(
            $container,
            $container->get(Street::class),
            $container->get(HandStreet::class),
            $container->get(PlayerAction::class),
            $container->get(Stack::class),
            $container->get(TableSeat::class),
            $container->get(BlindService::class)
        );
    }
}
