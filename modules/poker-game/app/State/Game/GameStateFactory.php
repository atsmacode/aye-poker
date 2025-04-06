<?php

namespace Atsmacode\PokerGame\State\Game;

use Atsmacode\PokerGame\GamePlay\Dealer\PokerDealer;
use Atsmacode\PokerGame\Repository\Game\GameRepository;
use Atsmacode\PokerGame\State\Player\PlayerState;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class GameStateFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): object
    {
        return new GameState(
            $container->get(GameRepository::class),
            $container->get(PokerDealer::class),
            $container->get(PlayerState::class),
            isset($options['hand']) ? $options['hand'] : null
        );
    }
}
