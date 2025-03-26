<?php

namespace Atsmacode\PokerGame\Models;

use Atsmacode\Framework\Dbal\Model;
class PlayerAction extends Model
{
    protected string $table = 'player_actions';
    private ?int     $bet_amount;
    private int      $active;
    private bool     $big_blind;
    private bool     $small_blind;
    private int      $player_id;
    private ?int     $action_id;
    private int      $hand_id;
    private int      $hand_street_id;
    private int      $table_seat_id;
    private ?string  $updated_at;

    public function getBetAmount(): ?int
    {
        return $this->bet_amount;
    }

    public function isBigBlind(): bool
    {
        return $this->big_blind;
    }

    public function isSmallBlind(): bool
    {
        return $this->small_blind;
    }

    public function getPlayerId(): int
    {
        return $this->player_id;
    }

    public function getActionId(): ?int
    {
        return $this->action_id;
    }

    public function getHandStreetId(): int
    {
        return $this->hand_street_id;
    }

    public function getTableSeatId(): int
    {
        return $this->table_seat_id;
    }

    public function getLatestAction(int $handId): self
    {
        try {
            $queryBuilder = $this->connection->createQueryBuilder();
            $queryBuilder
                ->select('*')
                ->from('player_actions', 'pa')
                ->leftJoin('pa', 'player_action_logs', 'pal', 'pa.id = pal.player_status_id')
                ->where('pa.hand_id = ' . $handId)
                ->orderBy('pal.id', 'DESC');

            $rows = $queryBuilder->executeQuery() ? $queryBuilder->fetchAssociative() : [];

            $this->content = $rows;
            
            $this->setModelProperties([$rows]);

            return $this;
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), ['class' => self::class, 'method' => __METHOD__]);
        }
    }

    public function getStreetActions(int $handStreetId): array
    {
        try {
            $queryBuilder = $this->connection->createQueryBuilder();
            $queryBuilder
                ->select('*')
                ->from('player_actions', 'pa')
                ->where('pa.hand_street_id = ' . $handStreetId);

            return $queryBuilder->executeQuery() ? $queryBuilder->fetchAllAssociative() : [];
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), ['class' => self::class, 'method' => __METHOD__]);
        }
    }
}
