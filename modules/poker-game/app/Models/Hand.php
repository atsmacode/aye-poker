<?php

namespace Atsmacode\PokerGame\Models;

use Atsmacode\Framework\Models\Model;
use Atsmacode\PokerGame\Repository\Game\GameRepository;

class Hand extends Model
{
    protected string $table = 'hands';
    private ?string $completed_on = null;
    private int $game_id;
    private ?Game $game;

    public function getGameId(): int
    {
        return $this->game_id;
    }

    public function setCompletedOn(string $completedOn): void
    {
        $this->completed_on = $completedOn;
    }

    public function getCompletedOn(): ?string
    {
        return $this->completed_on;
    }

    public function find(array $data): ?Hand
    {
        return parent::find($data); /* @phpstan-ignore return.type */
    }

    public function loadGame(): self
    {
        $gameRepo = $this->container->get(GameRepository::class);

        $this->game = $gameRepo->getGame($this->game_id);

        return $this;
    }

    public function getGame(): ?Game
    {
        if (isset($this->game)) {
            return $this->game;
        }

        return $this
            ->loadGame()
            ->getGame();
    }

    public function streets(): ?array
    {
        try {
            $queryBuilder = $this->connection->createQueryBuilder();
            $queryBuilder
                ->select('hs.*')
                ->from('hand_streets', 'hs')
                ->leftJoin('hs', 'hands', 'h', 'hs.hand_id = h.id')
                ->where('hs.hand_id = '.$queryBuilder->createNamedParameter($this->id));

            return $queryBuilder->executeStatement() ? $queryBuilder->fetchAllAssociative() : [];
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), ['class' => self::class, 'method' => __METHOD__]);

            return null;
        }
    }

    public function pot(): ?array
    {
        try {
            $queryBuilder = $this->connection->createQueryBuilder();
            $queryBuilder
                ->select('p.*')
                ->from('pots', 'p')
                ->leftJoin('p', 'hands', 'h', 'p.hand_id = h.id')
                ->where('p.hand_id = '.$queryBuilder->createNamedParameter($this->id));

            return $queryBuilder->executeStatement() ? $queryBuilder->fetchAssociative() : [];
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), ['class' => self::class, 'method' => __METHOD__]);

            return null;
        }
    }

    public function complete(): ?int
    {
        try {
            $queryBuilder = $this->connection->createQueryBuilder();
            $queryBuilder
                ->update('hands')
                ->set('completed_on', 'NOW()')
                ->where('id = '.$queryBuilder->createNamedParameter($this->id));

            return $queryBuilder->executeStatement();
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), ['class' => self::class, 'method' => __METHOD__]);

            return null;
        }
    }

    public function getDealer(): ?array
    {
        try {
            $queryBuilder = $this->connection->createQueryBuilder();
            $queryBuilder
                ->select('pa.*')
                ->from('player_actions', 'pa')
                ->leftJoin('pa', 'table_seats', 'ts', 'pa.table_seat_id = ts.id')
                ->where('pa.hand_id = '.$queryBuilder->createNamedParameter($this->id))
                ->andWhere('ts.is_dealer = 1');

            return $queryBuilder->executeStatement() ? $queryBuilder->fetchAssociative() : [];
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), ['class' => self::class, 'method' => __METHOD__]);

            return null;
        }
    }

    public function getPlayers(): ?array
    {
        try {
            $queryBuilder = $this->connection->createQueryBuilder();
            $queryBuilder
                ->select(
                    'ts.can_continue',
                    'ts.is_dealer',
                    'ts.player_id',
                    'ts.table_id',
                    'pa.bet_amount',
                    'pa.active',
                    'pa.big_blind',
                    'pa.small_blind',
                    'pa.action_id',
                    'pa.hand_id',
                    'pa.hand_street_id',
                    'pa.id player_action_id',
                    'ts.id table_seat_id',
                    's.amount stack',
                    'a.name actionName',
                    'p.name playerName',
                    'ts.number seat_number'
                )
                ->from('table_seats', 'ts')
                ->leftJoin('ts', 'player_actions', 'pa', 'ts.id = pa.table_seat_id')
                ->leftJoin('pa', 'players', 'p', 'pa.player_id = p.id')
                ->leftJoin('pa', 'stacks', 's', 'pa.player_id = s.player_id AND ts.table_id = s.table_id')
                ->leftJoin('pa', 'actions', 'a', 'pa.action_id = a.id')
                ->where('pa.hand_id = '.$queryBuilder->createNamedParameter($this->id))
                ->orderBy('ts.id', 'ASC');

            return $queryBuilder->executeStatement() ? $queryBuilder->fetchAllAssociative() : [];
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), ['class' => self::class, 'method' => __METHOD__]);

            return null;
        }
    }

    public function getCommunityCards(): ?array
    {
        try {
            $queryBuilder = $this->connection->createQueryBuilder();
            $queryBuilder
                ->select(
                    'c.*',
                    'r.name rankName',
                    'r.abbreviation rankAbbreviation',
                    's.name suit',
                    's.abbreviation suitAbbreviation',
                    'r.ranking ranking '
                )
                ->from('hand_street_cards', 'hsc')
                ->leftJoin('hsc', 'hand_streets', 'hs', 'hsc.hand_street_id = hs.id')
                ->leftJoin('hs', 'hands', 'h', 'hs.hand_id = h.id')
                ->leftJoin('hsc', 'cards', 'c', 'hsc.card_id = c.id')
                ->leftJoin('c', 'ranks', 'r', 'c.rank_id = r.id')
                ->leftJoin('c', 'suits', 's', 'c.suit_id = s.id')
                ->where('h.id = '.$queryBuilder->createNamedParameter($this->id))
                ->orderBy('hsc.id', 'ASC');

            return $queryBuilder->executeStatement() ? $queryBuilder->fetchAllAssociative() : [];
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), ['class' => self::class, 'method' => __METHOD__]);

            return null;
        }
    }
}
