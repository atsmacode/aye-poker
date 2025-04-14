<?php

namespace App\Security;

use App\Entity\User;
use App\Entity\UserPlayer;
use App\Service\PokerGame;
use App\Repository\UserPlayerRepository;
use Atsmacode\PokerGame\Enums\GameMode;
use Atsmacode\PokerGame\Models\PlayerAction;
use Atsmacode\PokerGame\Repository\Game\GameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PlayerActionVoter extends Voter
{
    const ACTION = 'action';

    private UserPlayerRepository $userPlayerRepository;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private PokerGame $pokerGame
    ) {
        $this->userPlayerRepository = $this->entityManager->getRepository(UserPlayer::class);
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, [self::ACTION])) { return false; }

        if ($subject['class'] !== PlayerAction::class) { return false; }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        $playerAction = (array) $subject['request'];

        $serviceManager = $this->pokerGame->getServiceManager();
        $game = $serviceManager->get(GameRepository::class)->getGame($playerAction['gameId']);

        if ($game->getMode() == GameMode::TEST->value) { return true; }

        // the user must be logged in; if not, deny access
        if (!$user instanceof User) { return false; } 

        return match($attribute) {
            self::ACTION => $this->canAction($playerAction, $user),
            default      => throw new \LogicException('This code should not be reached!')
        };
    }

    private function canAction(array $playerAction, User $user): bool
    {
        if (true === in_array('ROLE_ADMIN', $user->getRoles())) { return true; }
        
        $userPlayer = $this->userPlayerRepository->findOneBy([
            'userId' => $user->getId()
        ]);

        return $userPlayer->getPlayerId() === $playerAction['player_id'];
    }
}