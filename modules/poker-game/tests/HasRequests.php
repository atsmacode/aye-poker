<?php

namespace Atsmacode\PokerGame\Tests;

use Symfony\Component\HttpFoundation\Request;

trait HasRequests
{
    protected function createRequest(array $params)
    {
        return new Request([], $params);
    }
}
