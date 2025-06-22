<?php

namespace Atsmacode\PokerGame\Handlers\Action;

use Atsmacode\PokerGame\Handlers\Bet\BetHandler;
use Atsmacode\PokerGame\Models\Hand;
use Atsmacode\PokerGame\Models\PlayerAction;
use Atsmacode\PokerGame\Models\PlayerActionLog;
use Atsmacode\PokerGame\Models\TableSeat;
use Atsmacode\PokerGame\Repository\Hand\HandRepository;
use Atsmacode\PokerGame\State\Game\GameState;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class ActionHandlerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): object
    {
        return new ActionHandler(
            isset($options['gameState']) ? $options['gameState'] : $container->get(GameState::class),
            $container->build(PlayerAction::class),
            $container->build(PlayerActionLog::class),
            $container->get(BetHandler::class),
            $container->build(TableSeat::class),
            $container->build(Hand::class)
        );
    }
}
