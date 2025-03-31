<?php

namespace Atsmacode\CardGames;

use Atsmacode\Framework\ConfigProvider;
use Laminas\ConfigAggregator\ConfigAggregator;

class CardGamesConfigProvider extends ConfigProvider
{
    public function get(): array
    {
        $aggregator = new ConfigAggregator([
            CardGamesConfig::class,
        ]);

        return $aggregator->getMergedConfig();
    }
}
