<?php
/**
 * Used for manually testing Mercure updates.
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;

class PublishController extends AbstractController
{
    #[Route('/publish', name: 'app_publish')]
    public function publish(HubInterface $hub): Response
    {
        $update = new Update(
            'player_action',
            json_encode(['status' => 'action...'])
        );

        $hub->publish($update);

        return new Response('published!');
    }
}