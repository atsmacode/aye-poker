<?php

namespace Atsmacode\PokerGame\Services\Sit;

use Atsmacode\PokerGame\Models\Hand;
use Atsmacode\PokerGame\Models\Player;
use Atsmacode\PokerGame\Handlers\SitHandler\SitHandler;
use Atsmacode\PokerGame\Repository\Table\TableRepository;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class SitServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): object
    {
        return new SitService(
            $container,
            $container->get(Hand::class),
            $container->get(TableRepository::class),
            $container->get(SitHandler::class),
            $container->get(Player::class)
        );
    }
}
