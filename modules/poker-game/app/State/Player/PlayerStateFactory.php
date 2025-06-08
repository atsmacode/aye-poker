<?php

namespace Atsmacode\PokerGame\State\Player;

use Atsmacode\PokerGame\Models\Player;
use Atsmacode\PokerGame\Repository\TableSeat\TableSeatRepository;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class PlayerStateFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): object
    {
        return new PlayerState(
            $container->build(TableSeatRepository::class),
            $container->build(Player::class)
        );
    }
}
