<?php

namespace Atsmacode\PokerGame\State\GameState;

use Atsmacode\PokerGame\GamePlay\Dealer\PokerDealer;
use Atsmacode\PokerGame\GameData\GameData;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class GameStateFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): object
    {
        return new GameState(
            $container->get(GameData::class),
            $container->get(PokerDealer::class),
            isset($options['hand']) ? $options['hand'] : null
        );
    }
}
