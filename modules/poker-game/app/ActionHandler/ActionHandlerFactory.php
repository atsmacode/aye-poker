<?php

namespace Atsmacode\PokerGame\ActionHandler;

use Atsmacode\PokerGame\BetHandler\BetHandler;
use Atsmacode\PokerGame\GameState\GameState;
use Atsmacode\PokerGame\Models\PlayerAction;
use Atsmacode\PokerGame\Models\PlayerActionLog;
use Atsmacode\PokerGame\Models\TableSeat;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class ActionHandlerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        return new ActionHandler(
            isset($options['gameState']) ? $options['gameState'] : $container->get(GameState::class),
            $container->get(PlayerAction::class),
            $container->get(PlayerActionLog::class),
            $container->get(BetHandler::class),
            $container->get(TableSeat::class)
        );
    }
}
