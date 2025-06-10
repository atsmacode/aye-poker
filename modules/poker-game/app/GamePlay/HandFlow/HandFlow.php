<?php

namespace Atsmacode\PokerGame\GamePlay\HandFlow;

use Atsmacode\PokerGame\Contracts\ProcessesGameState;
use Atsmacode\PokerGame\GamePlay\GameStyle\GameStyle;
use Atsmacode\PokerGame\Repository\TableSeat\TableSeatRepository;
use Atsmacode\PokerGame\State\Game\GameState;

/**
 * Responsible for deciding what happens next in a hand based on the GameState.
 */
class HandFlow implements ProcessesGameState
{
    public function __construct(
        private GameState $gameState,
        GameStyle $gameStyle,
        private Start $start,
        private NewStreet $newStreet,
        private Showdown $showdown,
        private TableSeatRepository $tableSeatRepo,
    ) {
        $this->gameState->setStyle($gameStyle);
        $this->gameState->setGameDealer();
    }

    public function process(GameState $gameState): GameState
    {
        $this->gameState = $gameState;

        if ($this->theLastHandWasCompleted() || ! $this->gameState->handWasActive()) {
            return $this->run($this->start);
        }

        if ($this->bigBlindRemainsPreFlop()) {
            $this->tableSeatRepo->bigBlindWins($this->gameState->getBigBlind()['table_seat_id']);

            return $this->run($this->showdown);
        }

        if ($this->readyForShowdown() || $this->onePlayerRemainsThatCanContinue()) {
            return $this->run($this->showdown);
        }

        if ($this->allActivePlayersCanContinue()) {
            return $this->run($this->newStreet);
        }

        return $this->run();
    }

    private function run(?ProcessesGameState $step = null): GameState
    {
        $this->gameState->loadCommunityCards();

        $this->gameState = $step ? $step->process($this->gameState) : $this->gameState;

        $this->handleNewStreet();

        return $this->gameState;
    }

    protected function readyForShowdown(): bool
    {
        return count($this->gameState->getHandStreets()) === count($this->gameState->getStyle()->getStreets())
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

    protected function bigBlindRemainsPreFlop(): bool
    {
        $this->gameState->loadPlayers();

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
            $this->gameState->setNewStreet(true);
        }
    }
}
