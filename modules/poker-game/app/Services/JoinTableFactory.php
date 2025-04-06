<?php

namespace Atsmacode\PokerGame\Services;

use Atsmacode\PokerGame\Models\Hand;
use Atsmacode\PokerGame\Models\Player;
use Atsmacode\PokerGame\Models\Table;
use Atsmacode\PokerGame\Handlers\SitHandler\SitHandler;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class JoinTableFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): object
    {
        return new JoinTable(
            $container,
            $container->get(Hand::class),
            $container->get(Table::class),
            $container->get(SitHandler::class),
            $container->get(Player::class)
        );
    }
}
