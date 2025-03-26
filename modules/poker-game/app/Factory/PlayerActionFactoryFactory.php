<?php

namespace Atsmacode\PokerGame\Factory;

use Atsmacode\PokerGame\Models\PlayerAction;
use Atsmacode\PokerGame\Models\PlayerActionLog;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class PlayerActionFactoryFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $playerActionModel    = $container->get(PlayerAction::class);
        $playerActionLogModel = $container->get(PlayerActionLog::class);

        return new PlayerActionFactory(
            $playerActionModel,
            $playerActionLogModel
        );
    }
}
