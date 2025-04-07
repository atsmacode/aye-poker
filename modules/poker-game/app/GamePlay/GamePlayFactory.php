<?php

namespace Atsmacode\PokerGame\GamePlay;

use Atsmacode\PokerGame\GamePlay\Dealer\PokerDealer;
use Atsmacode\PokerGame\GamePlay\HandStep\NewStreet;
use Atsmacode\PokerGame\GamePlay\HandStep\Showdown;
use Atsmacode\PokerGame\GamePlay\HandStep\Start;
use Atsmacode\PokerGame\Repository\Game\GameRepository;
use Atsmacode\PokerGame\Repository\TableSeat\TableSeatRepository;
use Atsmacode\PokerGame\State\Game\GameState;
use Atsmacode\PokerGame\State\Player\PlayerState;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class GamePlayFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): object
    {
        $gameState = isset($options['gameState']) ? $options['gameState'] : new GameState(
            $container->get(GameRepository::class),
            $container->get(PokerDealer::class),
            $container->get(PlayerState::class),
            isset($options['hand']) ? $options['hand'] : null
        );

        return new GamePlay(
            $gameState,
            $options['game'],
            $container->get(Start::class),
            $container->get(NewStreet::class),
            $container->get(Showdown::class),
            $container->get(TableSeatRepository::class)
        );
    }
}
