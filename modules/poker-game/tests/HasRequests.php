<?php

namespace Atsmacode\PokerGame\Tests;

use Symfony\Component\HttpFoundation\Request;

trait HasRequests
{
    protected function createRequest(array $params)
    {
        return new Request([], $params);
    }

    protected function post(array $params)
    {
        return Request::create(
            uri: '',
            method: 'POST',
            content: json_encode($params)
        );
    }
}
