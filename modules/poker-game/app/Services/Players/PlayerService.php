<?php

namespace Atsmacode\PokerGame\Services\Players;

use Atsmacode\PokerGame\Repository\Players\PlayerRepository;

class PlayerService
{
    public function __construct(private PlayerRepository $playerRepo)
    {
    }

    public function getTestPlayers(): ?array
    {
        return $this->playerRepo->getTestPlayers();
    }
}
