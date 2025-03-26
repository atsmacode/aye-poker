<?php

namespace Atsmacode\Framework;

class FrameworkConfig
{
    const CONFIG_REF = 'config/framework.php';

    public function __invoke()
    {
        $config = require(FrameworkConfig::CONFIG_REF);

        return $config['framework'];
    }
}
