<?php

namespace Atsmacode\PokerGame\GamePlay;

use Atsmacode\PokerGame\Game\Game;
use Atsmacode\PokerGame\GameState\GameState;
use Atsmacode\PokerGame\GamePlay\HandStep\HandStep;
use Atsmacode\PokerGame\GamePlay\HandStep\NewStreet;
use Atsmacode\PokerGame\GamePlay\HandStep\Showdown;
use Atsmacode\PokerGame\GamePlay\HandStep\Start;
use Atsmacode\PokerGame\Models\TableSeat;
use Atsmacode\PokerGame\PlayerHandler\PlayerHandler;

/**
 * Responsible for deciding what happens next in a hand and
 * providing the response to the front-end application.
 */
class GamePlay
{
    public function __construct(
        private GameState $gameState,
        Game $game,
        private Start $start,
        private NewStreet $newStreet,
        private Showdown $showdown,
        private PlayerHandler $playerHandler,
        private TableSeat $tableSeats,
    ) {
        $this->gameState->setGame($game);
        $this->gameState->setGameDealer();
    }

    public function setGameState(GameState $gameState): void
    {
        $this->gameState = $gameState;
    }

    public function response(?HandStep $step = null, ?TableSeat $currentDealer = null): array
    {
        $this->gameState->setCommunityCards();

        $this->gameState = $step ? $step->handle($this->gameState, $currentDealer) : $this->gameState;

        $this->handleNewStreet();

        return [
            'pot' => $this->gameState->getPot(),
            'communityCards' => $this->gameState->getCommunityCards(),
            'players' => $this->playerHandler->handle($this->gameState),
            'winner' => $this->gameState->getWinner(),
            'sittingOut' => $this->gameState->getSittingOutPlayers(),
        ];
    }

    /** Specific start method to start new hand on page refresh in SitController */
    public function start(?TableSeat $currentDealer = null): array
    {
        return $this->response($this->start, $currentDealer);
    }

    public function play(GameState $gameState, ?TableSeat $currentDealer = null): array
    {
        $this->gameState = $gameState;

        if ($this->theLastHandWasCompleted()) {
            return $this->response($this->start, $currentDealer);
        }

        if ($this->theBigBlindIsTheOnlyActivePlayerRemainingPreFlop()) {
            $this->tableSeats->bigBlindWins($this->gameState->getBigBlind()['table_seat_id']);

            return $this->response($this->showdown);
        }

        if ($this->readyForShowdown() || $this->onePlayerRemainsThatCanContinue()) {
            return $this->response($this->showdown);
        }

        if ($this->allActivePlayersCanContinue()) {
            return $this->response($this->newStreet);
        }

        return $this->response();
    }

    protected function readyForShowdown(): bool
    {
        return count($this->gameState->getHandStreets()) === count($this->gameState->getGame()->getStreets())
            && count($this->gameState->getActivePlayers()) === count($this->gameState->getContinuingPlayers());
    }

    protected function onePlayerRemainsThatCanContinue(): bool
    {
        return count($this->gameState->getActivePlayers()) === count($this->gameState->getContinuingPlayers())
            && 1 === count($this->gameState->getContinuingPlayers());
    }

    protected function allActivePlayersCanContinue(): bool
    {
        return count($this->gameState->getActivePlayers()) === count($this->gameState->getContinuingPlayers());
    }

    protected function theBigBlindIsTheOnlyActivePlayerRemainingPreFlop(): bool
    {
        $this->gameState->setPlayers();

        $activePlayers = array_values(array_filter($this->gameState->getPlayers(), function ($player) {
            return 1 === $player['active'];
        }));

        return 1 === count($activePlayers) && 1 === $activePlayers[0]['big_blind'];
    }

    protected function theLastHandWasCompleted(): bool
    {
        return null !== $this->gameState->getHand()->getCompletedOn();
    }

    private function handleNewStreet(): void
    {
        $handStreets = $this->gameState->getHandStreets();
        $latestStreet = array_pop($handStreets);

        if (0 < count($handStreets) && $this->gameState->streetHasNoActions($latestStreet['id'])) {
            $this->gameState->setNewStreet();
        }
    }
}
