<?php

declare(strict_types=1);

namespace Atsmacode\PokerGame\State\Game;

use Atsmacode\PokerGame\GamePlay\Dealer\PokerDealer;
use Atsmacode\PokerGame\GamePlay\GameStyle\GameStyle;
use Atsmacode\PokerGame\Models\Hand;
use Atsmacode\PokerGame\Models\PlayerAction;
use Atsmacode\PokerGame\Models\Table;
use Atsmacode\PokerGame\Repository\GameState\GameStateRepository;
use Atsmacode\PokerGame\State\Player\PlayerState;

/**
 * Mutable. Holds the state of the Game throughout the lifecycle.
 *
 * Most significantly, GameState is calculated based on the latest player action,
 * and is passed to the next required HandFlow identified by GamePlay.
 */
class GameState
{
    private array $communityCards = [];
    private array $wholeCards = [];
    private ?array $winner = null;
    private PlayerAction $latestAction;
    private int $tableId;
    private int $handId;
    private array $seats;
    private ?array $actions;
    private array $handStreets;
    private array $players;
    private array $stacks;
    private bool $newStreet = false;
    private GameStyle $gameStyle;
    private PokerDealer $dealer;
    private array $bigBlind;
    private Table $table;
    private bool $handWasActive = false; // Can be used to detect if we are starting/continuing a hand in HandFlow
    private bool $testMode = false; // Can be used to skip logic for unit tests

    public function __construct(
        private GameStateRepository $gameRepo,
        private PokerDealer $pokerDealer,
        private PlayerState $playerState,
        private ?Hand $hand,
    ) {
        if ($hand) {
            $this->initiate($hand);
        }
    }

    public function initiate(?Hand $hand): void
    {
        $this->setHand($hand);
        $this->tableId = $hand->getTableId();
        $this->handId = (int) $hand->getId();
        $this->seats = $this->gameRepo->getSeats($this->tableId);
        $this->handStreets = $this->hand->streets();
    }

    public function getGameMode(): int
    {
        return $this->gameRepo->getTableGame($this->tableId)->getMode();
    }

    public function getSeat(int $number): ?array
    {
        $key = array_search($number, array_column($this->seats, 'number'));

        if (false !== $key) {
            return $this->seats[$key];
        }

        return null;
    }

    public function getDealer(): ?array
    {
        $key = array_search(1, array_column($this->seats, 'is_dealer'));

        if (false !== $key) {
            return $this->seats[$key];
        }

        return null;
    }

    public function getSeatAction(int $seatId): ?array
    {
        $key = array_search($seatId, array_column($this->actions, 'table_seat_id'));

        if (false !== $key) {
            return $this->actions[$key];
        }

        return null;
    }

    public function setActions(?array $actions): void
    {
        $this->actions = $actions;
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

    public function loadHandStreets(): self
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
            : $this->gameRepo->getLatestAction($this->handId);
    }

    public function getPot(): int
    {
        $pot = $this->hand->pot();

        return isset($pot['amount']) ? $pot['amount'] : 0;
    }

    public function loadCommunityCards(): self
    {
        $this->communityCards = $this->hand->getCommunityCards();

        return $this;
    }

    public function getCommunityCards(): array
    {
        return $this->communityCards;
    }

    public function loadWholeCards(): self
    {
        $this->wholeCards = $this->gameRepo->getWholeCards($this->getPlayers(), $this->handId);

        return $this;
    }

    public function getWholeCards(): array
    {
        return $this->wholeCards;
    }

    public function loadPlayers(): self
    {
        $this->players = $this->hand->getPlayers();

        return $this;
    }

    public function getPlayers(): array
    {
        return $this->players;
    }

    public function getSittingOutPlayers(): array
    {
        return array_diff(
            array_column($this->getSeats(), 'player_id'),
            array_column($this->getPlayers(), 'player_id')
        );
    }

    public function getActivePlayers(): array
    {
        return array_filter($this->players, function ($player) {
            return 1 === $player['active'];
        });
    }

    public function getContinuingPlayers(): array
    {
        return array_filter($this->players, function ($player) {
            return 1 === $player['active'] && 1 === $player['can_continue'];
        });
    }

    public function firstActivePlayer(): ?array
    {
        $key = array_search(1, array_column($this->players, 'active'));

        if (false !== $key) {
            return $this->players[$key];
        }

        return null;
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

    public function setNewStreet(bool $newStreet): self
    {
        $this->newStreet = $newStreet;

        return $this;
    }

    /** isNewStreet should be set at the time a new street is dealt. */
    public function isNewStreet(): bool
    {
        return $this->newStreet;
    }

    public function setStyle(GameStyle $gameStyle): void
    {
        $this->gameStyle = $gameStyle;
    }

    public function getStyle(): GameStyle
    {
        return $this->gameStyle;
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
        $this->bigBlind = $this->gameRepo->getBigBlind($this->handId);
    }

    public function getBigBlind(): array
    {
        return $this->bigBlind;
    }

    public function streetHasNoActions(int $handStreetId): bool
    {
        $actions = $this->gameRepo->getStreetActions($handStreetId);
        $acted = array_filter($actions, function ($value) { return null !== $value['action_id']; });

        return 0 === count($acted);
    }

    public function getPlayerState(): array
    {
        return $this->playerState->get($this);
    }

    public function handWasActive(): bool
    {
        return $this->handWasActive;
    }

    public function setHandWasActive(bool $was): void
    {
        $this->handWasActive = $was;
    }

    public function testMode(): bool
    {
        return $this->testMode;
    }

    public function setTestMode(bool $testMode): self
    {
        $this->testMode = $testMode;

        return $this;
    }
}
