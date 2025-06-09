<?php

namespace Atsmacode\PokerGame\GamePlay\HandFlow;

use Atsmacode\PokerGame\GamePlay\Dealer\PokerDealer;
use Atsmacode\PokerGame\Repository\GameState\GameStateRepository;
use Atsmacode\PokerGame\Repository\TableSeat\TableSeatRepository;
use Atsmacode\PokerGame\State\Game\GameState;
use Atsmacode\PokerGame\State\Player\PlayerState;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class HandFlowFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): object
    {
        $gameState = isset($options['gameState']) ? $options['gameState'] : new GameState(
            $container->get(GameStateRepository::class),
            $container->get(PokerDealer::class),
            $container->get(PlayerState::class),
            isset($options['hand']) ? $options['hand'] : null
        );

        return new HandFlow(
            $gameState,
            $options['game'],
            $container->get(Start::class),
            $container->get(NewStreet::class),
            $container->get(Showdown::class),
            $container->get(TableSeatRepository::class)
        );
    }
}
