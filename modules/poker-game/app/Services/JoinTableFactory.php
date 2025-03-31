<?php

namespace Atsmacode\PokerGame\Services;

use Atsmacode\PokerGame\Models\Pot;
use Atsmacode\PokerGame\Models\Stack;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class JoinTableFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): object
    {
        return new $requestedName(
            $container
        );
    }
}
