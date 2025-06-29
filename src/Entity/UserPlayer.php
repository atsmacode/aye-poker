<?php

namespace App\Entity;

use App\Entity\User;
use App\Repository\UserPlayerRepository;
use App\Service\PokerGame;
use Atsmacode\PokerGame\Models\Player;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserPlayerRepository::class)]
class UserPlayer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $userId = null;

    #[ORM\OneToOne(targetEntity: User::class, inversedBy: 'userPlayer')]
    private User $user;

    #[ORM\Column]
    private ?int $playerId = null;

    private string $playerName;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayerId(): ?int
    {
        return $this->playerId;
    }

    public function setPlayerId(int $playerId): self
    {
        $this->playerId = $playerId;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getPlayerName(): string
    {
        if (isset($this->playerName)) {
            return $this->playerName;
        }

        $pokerGame = new PokerGame();
        $players = $pokerGame->get(Player::class);
        $player = $players->find(['id' => $this->playerId]);

        return $player->getName();
    }
}
