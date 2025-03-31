<?php

namespace Atsmacode\Framework;

class FrameworkConfig
{
    public const CONFIG_REF = 'config/framework.php';

    public function __invoke(): array
    {
        $config = require FrameworkConfig::CONFIG_REF;

        return $config['framework'];
    }
}
