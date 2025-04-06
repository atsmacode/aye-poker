<?php

namespace Atsmacode\PokerGame\Repository\Game;

use Atsmacode\PokerGame\Models\Hand;
use Atsmacode\PokerGame\Models\TableSeat;
use Atsmacode\PokerGame\Repository\PlayerAction\PlayerActionRepository;
use Atsmacode\PokerGame\Repository\Table\TableRepository;
use Atsmacode\PokerGame\Repository\WholeCard\WholeCardRepository;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class GameRepositoryFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): object
    {
        return new GameRepository(
            $container->get(Hand::class),
            $container->get(TableRepository::class),
            $container->get(WholeCardRepository::class),
            $container->get(TableSeat::class),
            $container->get(PlayerActionRepository::class)
        );
    }
}
