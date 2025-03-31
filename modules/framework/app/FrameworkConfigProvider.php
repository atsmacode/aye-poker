<?php

namespace Atsmacode\Framework;

use Laminas\ConfigAggregator\ConfigAggregator;

class FrameworkConfigProvider extends ConfigProvider
{
    public function get(): array
    {
        $aggregator = new ConfigAggregator([
            FrameworkConfig::class,
        ]);

        return $aggregator->getMergedConfig();
    }
}
