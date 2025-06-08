<?php

namespace Atsmacode\PokerGame\Handlers\Sit;

use Atsmacode\PokerGame\Models\Hand;
use Atsmacode\PokerGame\Repository\TableSeat\TableSeatRepository;
use Atsmacode\PokerGame\State\Game\GameState;
use Atsmacode\PokerGame\State\Player\PlayerState;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class SitHandlerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): object
    {
        return new SitHandler(
            $container->get(GameState::class),
            $container->get(Hand::class),
            $container->get(TableSeatRepository::class),
            $container->get(PlayerState::class)
        );
    }
}
