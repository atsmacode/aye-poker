<?php

namespace Atsmacode\PokerGame\Dealer;

use Atsmacode\PokerGame\Models\Deck;
use Atsmacode\PokerGame\Models\HandStreetCard;
use Atsmacode\PokerGame\Models\WholeCard;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class PokerDealerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $wholeCardModel      = $container->get(WholeCard::class);
        $handStreetCardModel = $container->get(HandStreetCard::class);
        $deckModel           = $container->get(Deck::class);

        return new PokerDealer(
            $wholeCardModel,
            $handStreetCardModel,
            $deckModel
        );
    }
}
