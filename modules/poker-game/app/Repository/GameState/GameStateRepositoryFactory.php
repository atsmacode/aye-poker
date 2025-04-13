<?php

namespace Atsmacode\PokerGame\Repository\GameState;

use Atsmacode\PokerGame\Models\Hand;
use Atsmacode\PokerGame\Repository\Game\GameRepository;
use Atsmacode\PokerGame\Repository\PlayerAction\PlayerActionRepository;
use Atsmacode\PokerGame\Repository\TableSeat\TableSeatRepository;
use Atsmacode\PokerGame\Repository\WholeCard\WholeCardRepository;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class GameStateRepositoryFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): object
    {
        return new GameStateRepository(
            $container->build(Hand::class),
            $container->get(TableSeatRepository::class),
            $container->get(WholeCardRepository::class),
            $container->get(PlayerActionRepository::class),
            $container->get(GameRepository::class)
        );
    }
}
