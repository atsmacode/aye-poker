<?php

namespace App\Service;

use App\Entity\UserPlayer;
use Atsmacode\PokerGame\Controllers\Player\Controller as PlayerController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class PokerPlayer
{
    public function __construct(private PokerGame $pokerGame, private EntityManagerInterface $entityManager) {}

     /** Manually calling poker-game controller, could be swapped for API client in future */
     public function create(Request $request, int $userId): void
     {
         $serviceManager = $this->pokerGame->getServiceManager();

         $request  = new Request(content: json_encode(['name' => $this->getUrlAsArray($request)['playername']]));
         $response = $serviceManager->get(PlayerController::class)->create($request);
         $playerId = json_decode($response->getContent(), true)['playerId'];

         $userPlayer = new UserPlayer();
         $userPlayer->setUserId($userId);
         $userPlayer->setPlayerId($playerId);

         $this->entityManager->persist($userPlayer);

         $this->entityManager->flush();
    }

    /** 
     * This method is based on the URL params generated 
     * by Symfony in the User registration form flow. 
     */
    private function getUrlAsArray(Request $request)
    {
        parse_str(urldecode($request->getContent()), $result);

        return $result['registration_form'];
    }
}