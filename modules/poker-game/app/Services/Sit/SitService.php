<?php

namespace Atsmacode\PokerGame\Services\Sit;

use Atsmacode\PokerGame\GamePlay\GamePlay;
use Atsmacode\PokerGame\GamePlay\GameStyle\PotLimitHoldEm;
use Atsmacode\PokerGame\Handlers\Sit\SitHandler;
use Atsmacode\PokerGame\Models\Hand;
use Atsmacode\PokerGame\Models\Player;
use Atsmacode\PokerGame\Models\TableSeat;
use Atsmacode\PokerGame\Repository\TableSeat\TableSeatRepository;
use Atsmacode\PokerGame\State\Game\GameState;
use Psr\Container\ContainerInterface;

/**
 * Key service for use in Controllers or applications using this package
 * internally. Handles a user visiting the page/table and initiates GamePlay response.
 */
class SitService
{
    private string $game = PotLimitHoldEm::class;

    public function __construct(
        private ContainerInterface $container,
        private Hand $hands,
        private TableSeatRepository $tableSeatRepo,
        private SitHandler $sitHandler,
        private Player $players,
    ) {
    }

    public function sit(
        ?int $tableId = null,
        ?TableSeat $currentDealer = null,
        ?int $playerId = null,
        ?int $gameId = null
    ): array {
        if (null !== $playerId) {
            $playerSeat = $this->sitHandler->handle($playerId);
            $tableId = $playerSeat->getTableId();

            if (2 > count($this->tableSeatRepo->hasMultiplePlayers($tableId))) {
                return [
                    'message' => 'Waiting for more players to join.',
                    'players' => $this->setWaitingPlayerData($playerId, $playerSeat->getId(), $playerSeat->getNumber()),
                ];
            }
        }

        $currentHand = $this->hands->find(['game_id' => $gameId, 'table_id' => $tableId, 'completed_on' => null]);
        $currentHandIsActive = $currentHand ?? false;

        $hand = $currentHandIsActive
            ? $currentHand
            : $this->hands->create(['table_id' => $tableId, 'game_id' => $gameId]);

        $gameState = $this->container->build(GameState::class, ['hand' => $hand]); /** @phpstan-ignore method.notFound */
        $gamePlayService = $this->container->build(GamePlay::class, [/* @phpstan-ignore method.notFound */
            'game' => $this->container->get($this->game),
            'gameState' => $gameState,
        ]);

        return $currentHandIsActive
            ? $gamePlayService->play($gameState)
            : $gamePlayService->start($currentDealer);
    }

    private function setWaitingPlayerData(int $playerId, int $tableSeatId, int $seatNumber): array
    {
        $playerName = $this->players->find(['id' => $playerId])->getName();

        return [
            $seatNumber => [
                'stack' => null,
                'name' => $playerName,
                'action_id' => null,
                'action_name' => null,
                'player_id' => $playerId,
                'table_seat_id' => $tableSeatId,
                'hand_street_id' => null,
                'bet_amount' => null,
                'active' => 0,
                'can_continue' => 0,
                'is_dealer' => 0,
                'big_blind' => 0,
                'small_blind' => 0,
                'whole_cards' => [],
                'action_on' => false,
                'availableOptions' => [],
            ],
        ];
    }
}
