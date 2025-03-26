<?php declare(strict_types=1);

namespace Atsmacode\PokerGame\GameState;

use Atsmacode\PokerGame\Dealer\PokerDealer;
use Atsmacode\PokerGame\Game\Game;
use Atsmacode\PokerGame\GameData\GameData;
use Atsmacode\PokerGame\Models\Hand;
use Atsmacode\PokerGame\Models\PlayerAction;
use Atsmacode\PokerGame\Models\Table;

class GameState
{
    private array         $communityCards = [];
    private array         $wholeCards = [];
    private ?array        $winner = null;
    private ?PlayerAction $latestAction;
    private int           $tableId;
    private int           $handId;
    private array         $seats;
    private ?array        $actions;
    private array         $handStreets;
    private array         $players;
    private array         $stacks;
    private bool          $newStreet = false;
    private Game          $game;
    private PokerDealer   $dealer;
    private array         $bigBlind;

    public function __construct(
        private GameData    $gameData,
        private PokerDealer $pokerDealer,
        private ?Hand       $hand
    ) {
        if ($hand) { $this->initiate($hand); }
    }

    public function initiate(Hand $hand)
    {
        $this->hand        = $hand;
        $this->tableId     = $hand->getTableId();
        $this->handId      = (int) $hand->getId();
        $this->seats       = $this->gameData->getSeats($this->tableId);
        $this->handStreets = $this->hand->streets();
    }

    public function getSeat(int $seatId)
    {
        $key = array_search($seatId, array_column($this->seats, 'id'));

        if ($key !== false) { return $this->seats[$key]; }

        return false;
    }

    public function getDealer()
    {
        $key = array_search(1, array_column($this->seats, 'is_dealer'));

        if ($key !== false) { return $this->seats[$key]; }

        return false;
    }

    public function getSeatAction(int $seatId)
    {
        $key = array_search($seatId, array_column($this->actions, 'table_seat_id'));

        if ($key !== false) { return $this->actions[$key]; }

        return false;
    }

    public function setHand(Hand $hand): void
    {
        $this->hand = $hand;
    }

    public function getHand(): ?Hand
    {
        return $this->hand;
    }

    public function setTable(Table $table): void
    {
        $this->table = $table;
    }

    public function getTable(): Table
    {
        return $this->table;
    }

    public function tableId(): int
    {
        return $this->tableId;
    }

    public function handId(): int
    {
        return $this->handId;
    }

    public function getSeats(): array
    {
        return $this->seats;
    }

    public function getHandStreets(): array
    {
        return $this->handStreets;
    }

    public function updateHandStreets(): self
    {
        $this->handStreets = $this->hand->streets();

        return $this;
    }

    public function incrementedHandStreets(): int
    {
        return count($this->handStreets) + 1;
    }

    public function handStreetCount(): int
    {
        return count($this->handStreets);
    }

    public function setLatestAction(PlayerAction $playerAction): void
    {
        $this->latestAction = $playerAction;
    }

    public function getLatestAction(): PlayerAction
    {
        return isset($this->latestAction)
            ? $this->latestAction
            : $this->gameData->getLatestAction($this->handId);
    }

    public function getPot(): int
    {
        $pot = $this->hand->pot();

        return isset($pot['amount']) ? $pot['amount'] : 0;
    }

    public function setCommunityCards(): self
    {
        $this->communityCards = $this->gameData->getCommunityCards($this->handId);

        return $this;
    }

    public function getCommunityCards(): array
    {
        return $this->communityCards;
    }

    public function setWholeCards(): self
    {
        $this->wholeCards = $this->gameData->getWholeCards($this->getPlayers(), $this->handId);

        return $this;
    }

    public function getWholeCards(): array
    {
        return $this->wholeCards;
    }

    public function setPlayers(): self
    {
        $this->players = $this->gameData->getPlayers($this->handId);

        return $this;
    }

    public function getPlayers(): array
    {
        return $this->players;
    }

    public function getSittingOutPlayers()
    {
        return array_diff(
            array_column($this->getSeats(), 'player_id'),
            array_column($this->getPlayers(), 'player_id')
        );
    }

    public function getActivePlayers(): array
    {
        return array_filter($this->players, function($player){
            return 1 === $player['active'];
        });
    }

    public function getContinuingPlayers(): array
    {
        return array_filter($this->players, function($player){
            return 1 === $player['active'] && 1 === $player['can_continue'];
        });
    }

    public function firstActivePlayer()
    {
        $key = array_search(1, array_column($this->players, 'active'));

        if ($key !== false) {
            return $this->players[$key];
        }

        return false;
    }

    public function setWinner(array $winner): void
    {
        $this->winner = $winner;
    }

    public function getWinner(): ?array
    {
        return $this->winner;
    }

    public function setStacks(array $stacks): void
    {
        $this->stacks = $stacks;
    }

    public function getStacks(): array
    {
        return $this->stacks;
    }

    public function setNewStreet(): void
    {
        $this->newStreet = true;
    }

    /** isNewStreet should be set at the time a new street is dealt. */
    public function isNewStreet(): bool
    {
        return $this->newStreet;
    }

    public function setGame(Game $game): void
    {
        $this->game = $game;
    }

    public function getGame(): Game
    {
        return $this->game;
    }

    public function setGameDealer(): void
    {
        $this->dealer = isset($this->handId)
            ? $this->pokerDealer->setSavedDeck($this->handId)
            : $this->pokerDealer;
    }

    public function getGameDealer(): PokerDealer
    {
        return $this->dealer;
    }

    public function setBigBlind(): void
    {
        $this->bigBlind = $this->gameData->getBigBlind($this->handId);
    }

    public function getBigBlind(): array
    {
        return $this->bigBlind;
    }

    public function isHeadsUp()
    {
        return 2 === count($this->getActivePlayers());
    }

    public function streetHasNoActions(int $handStreetId): bool
    {
        $actions = $this->gameData->getStreetActions($handStreetId);
        $acted   = array_filter($actions, function($value){ return null !== $value['action_id']; });

        return 0 === count($acted);
    }
}
