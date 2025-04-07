<?php

namespace Atsmacode\PokerGame\GamePlay\Dealer;

use Atsmacode\PokerGame\Models\Deck;
use Atsmacode\PokerGame\Models\HandStreetCard;
use Atsmacode\PokerGame\Models\WholeCard;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class PokerDealerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): object
    {
        return new PokerDealer(
            $container->build(WholeCard::class),
            $container->build(HandStreetCard::class),
            $container->build(Deck::class)
        );
    }
}
