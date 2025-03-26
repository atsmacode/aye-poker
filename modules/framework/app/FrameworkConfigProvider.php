<?php

namespace Atsmacode\Framework;

use Atsmacode\Framework\ConfigProvider;
use Laminas\ConfigAggregator\ConfigAggregator;

class FrameworkConfigProvider extends ConfigProvider
{
    public function get()
    {
        $aggregator = new ConfigAggregator([
            FrameworkConfig::class
        ]);

        return $aggregator->getMergedConfig(); 
    }
}
