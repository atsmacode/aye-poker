<?php

declare(strict_types=1);

namespace Atsmacode\PokerGame\State\PlayerState;

use Atsmacode\PokerGame\Constants\Action;
use Atsmacode\PokerGame\State\GameState\GameState;
use Atsmacode\PokerGame\Models\TableSeat;

/**
 * Responsible for returning the status and options for the players based on the GameState.
 */
class PlayerState implements PlayerStateInterface
{
    private GameState $gameState;

    public function __construct(
        private TableSeat $tableSeats,
    ) {
    }

    public function get(GameState $gameState): array
    {
        $this->gameState = $gameState;
        $playerData = [];
        $actionOnGet = $this->getActionOn();

        $this->gameState->setWholeCards();

        foreach ($this->gameState->getPlayers() as $playerAction) {
            $actionOn = $actionOnGet && $actionOnGet['player_id'] === $playerAction['player_id'] ? true : false;
            $actionName = $playerAction['action_id'] ? $playerAction['actionName'] : null;
            $stack = $playerAction['stack'];
            $wholeCards = isset($this->gameState->getWholeCards()[$playerAction['player_id']])
                ? $this->gameState->getWholeCards()[$playerAction['player_id']]
                : [];

            $playerData[$playerAction['seat_number']] = [
                'stack' => $stack ?? null,
                'name' => $playerAction['playerName'],
                'action_id' => $playerAction['action_id'],
                'action_name' => $actionName,
                'player_id' => $playerAction['player_id'],
                'table_seat_id' => $playerAction['table_seat_id'],
                'hand_street_id' => $playerAction['hand_street_id'],
                'bet_amount' => $playerAction['bet_amount'],
                'active' => $playerAction['active'],
                'can_continue' => $playerAction['can_continue'],
                'is_dealer' => $playerAction['is_dealer'],
                'big_blind' => $playerAction['big_blind'],
                'small_blind' => $playerAction['small_blind'],
                'whole_cards' => $wholeCards,
                'action_on' => $actionOn,
                'availableOptions' => $actionOn ? $this->getOptionsViaLatestAction($playerAction) : [],
            ];
        }

        return $playerData;
    }

    private function getActionOn(): array
    {
        $dealer = $this->gameState->getHand()->getDealer();
        $firstActivePlayer = $this->gameState->firstActivePlayer();
        $lastToAct = $this->gameState->getLatestAction()->getTableSeatId();

        if ($this->gameState->isNewStreet()) {
            return $this->getActionOnForNewStreet($dealer, $firstActivePlayer);
        }

        $activePlayersAfterLastToAct = array_filter($this->gameState->getActivePlayers(), function ($value) use ($lastToAct) {
            return $value['table_seat_id'] > $lastToAct;
        });

        $playerAfterLastToAct = count($activePlayersAfterLastToAct) ? array_shift($activePlayersAfterLastToAct) : null;

        return $playerAfterLastToAct ?: $firstActivePlayer;
    }

    private function getActionOnForNewStreet(array $dealer, array $firstActivePlayer): array
    {
        $playerAfterDealer = $this->tableSeats->playerAfterDealer($this->gameState->handId(), $dealer['table_seat_id']);

        return 0 < count($playerAfterDealer->getContent()) ? $playerAfterDealer->getContent()[0] : $firstActivePlayer;
    }

    private function getOptionsViaLatestAction(array $playerAction): array
    {
        $latestAction = $this->gameState->getLatestAction();
        $continuingBetters = $this->tableSeats->getContinuingBetters((string) $this->gameState->getHand()->getId());
        $playerActions = $this->gameState->getPlayers();

        if ($this->gameState->isNewStreet()) {
            return [Action::FOLD, Action::CHECK, Action::BET];
        }

        /* BB is the only player that can fold / check / raise pre-flop */
        if (1 === count($this->gameState->getHandStreets()) && !$playerAction['big_blind']) {
            return [Action::FOLD, Action::CALL, Action::RAISE];
        }

        switch ($latestAction->getActionId()) {
            case Action::CALL['id']:
                if ($this->isBigBlindOnUnRaisedFirstStreet($playerActions, $playerAction)) {
                    return [Action::FOLD, Action::CHECK, Action::RAISE];
                }

                return [Action::FOLD, Action::CALL, Action::RAISE];
            case Action::BET['id']:
            case Action::RAISE['id']:
                return [Action::FOLD, Action::CALL, Action::RAISE];
            case Action::CHECK['id']:
                return [Action::FOLD, Action::CHECK, Action::BET];
            default:
                if ($this->isBigBlindOnUnRaisedFirstStreet($playerActions, $playerAction)) {
                    return [Action::FOLD, Action::CHECK, Action::RAISE];
                }

                /* Latest action may be a fold, so we need to check for raisers/callers/bettters before the folder. */
                if (0 < count($continuingBetters)) {
                    return [Action::FOLD, Action::CALL, Action::RAISE];
                }

                return [Action::FOLD, Action::CHECK, Action::BET];
        }
    }

    private function isBigBlindOnUnRaisedFirstStreet(array $playerActions, array $playerAction): bool
    {
        return 1 === count($this->gameState->getHandStreets())
            && $playerAction['big_blind']
            && !in_array(Action::RAISE['id'], array_column($playerActions, 'action_id'));
    }
}
