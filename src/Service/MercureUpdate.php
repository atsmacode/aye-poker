<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

class MercureUpdate
{
    public function __construct(private HubInterface $hub){}

    public function publish(string $content): Response
    {
        try {
            $response = $this->hub->publish(new Update('player_action', $content));
        } catch (\Exception $e) {
            $response = $e->getMessage();
        }

        return new Response($response);
    }
}