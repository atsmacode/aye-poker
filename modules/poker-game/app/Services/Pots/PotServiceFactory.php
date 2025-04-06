<?php

namespace Atsmacode\PokerGame\Services\Pots;

use Atsmacode\PokerGame\Models\Pot;
use Atsmacode\PokerGame\Repository\Stack\StackRepository;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class PotServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): object
    {
        return new PotService(
            $container->get(StackRepository::class),
            $container->get(Pot::class)
        );
    }
}
