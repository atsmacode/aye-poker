<?php

namespace Atsmacode\PokerGame\Services\GamePlay;

use Atsmacode\PokerGame\Handlers\ActionHandler\ActionHandler;
use Atsmacode\PokerGame\Repository\Hand\HandRepository;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class GamePlayServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): object
    {
        return new GamePlayService(
            $container,
            $container->get(ActionHandler::class),
            $container->get(HandRepository::class)
        );
    }
}
