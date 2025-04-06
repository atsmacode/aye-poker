<?php

namespace Atsmacode\PokerGame\Handlers\BetHandler;

use Atsmacode\PokerGame\Models\Stack;
use Atsmacode\PokerGame\Services\PotService\PotService;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class BetHandlerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): object
    {
        return new BetHandler(
            $container->get(PotService::class),
            $container->get(Stack::class)
        );
    }
}
