<?php

namespace Atsmacode\PokerGame\PotHandler;

use Atsmacode\PokerGame\Models\Pot;
use Atsmacode\PokerGame\Models\Stack;
use Atsmacode\PokerGame\PotHandler\PotHandler;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class PotHandlerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $stackModel = $container->get(Stack::class);
        $potModel   = $container->get(Pot::class);

        return new PotHandler(
            $stackModel,
            $potModel
        );
    }
}
