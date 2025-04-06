<?php

namespace Atsmacode\PokerGame\Handlers\Bet;

use Atsmacode\PokerGame\Repository\Stack\StackRepository;
use Atsmacode\PokerGame\Services\Pots\PotService;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class BetHandlerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): object
    {
        return new BetHandler(
            $container->get(PotService::class),
            $container->get(StackRepository::class)
        );
    }
}
