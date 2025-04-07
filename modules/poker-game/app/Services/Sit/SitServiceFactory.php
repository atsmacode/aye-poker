<?php

namespace Atsmacode\PokerGame\Services\Sit;

use Atsmacode\PokerGame\Handlers\Sit\SitHandler;
use Atsmacode\PokerGame\Models\Hand;
use Atsmacode\PokerGame\Models\Player;
use Atsmacode\PokerGame\Repository\TableSeat\TableSeatRepository;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class SitServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): object
    {
        return new SitService(
            $container,
            $container->build(Hand::class),
            $container->get(TableSeatRepository::class),
            $container->get(SitHandler::class),
            $container->build(Player::class)
        );
    }
}
