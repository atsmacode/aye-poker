<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\UserPlayer;
use App\Service\PokerGame;
use Atsmacode\PokerGame\Services\Players\PlayerService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private PokerGame $pokerGame
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        // Seed test user players for real mode
        $players = $this->pokerGame
            ->get(PlayerService::class)
            ->getTestPlayers();

        foreach ($players as $player) {
            $user = new User();
            $user->setEmail(sprintf('%s@aye.com', str_replace(' ', '', strtolower($player['name']))));
    
            $hashedPassword = $this->passwordHasher->hashPassword($user, 'password');
            $user->setPassword($hashedPassword);

            $userPlayer = new UserPlayer();
            $userPlayer->setPlayerId($player['id']);
            $userPlayer->setUser($user);

            $manager->persist($userPlayer);
            $manager->persist($user);
            $manager->flush();
        }

    }
}
