<?php

namespace Atsmacode\Framework;

abstract class ConfigProvider
{
    abstract public function get(): array;
}
