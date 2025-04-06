<?php

namespace Atsmacode\PokerGame\Repository\Hand;

use Atsmacode\Framework\Database\Database;
use Atsmacode\PokerGame\Models\Hand;
use Psr\Log\LoggerInterface;

class HandRepository extends Database
{
    public function __construct(
        protected mixed $connection,
        protected LoggerInterface $logger,
        private Hand $hands
    ) {
        parent::__construct($connection, $logger);
    }

    public function getLatest(): ?Hand
    {
        $query = sprintf('
            SELECT * FROM hands ORDER BY id DESC LIMIT 1
        ');

        try {
            /**
             * @todo Using query builder here returns no results and causes:
             * SQLSTATE[HY000]: General error: 2014 Cannot execute queries while
             * other unbuffered queries are active.
             */
            // $queryBuilder = $this->connection->createQueryBuilder();
            // $queryBuilder
            //     ->select('*')
            //     ->from('hands')
            //     ->orderBy('id', 'DESC')
            //     ->setMaxResults(1);

            // $row = $queryBuilder->executeStatement() ? $queryBuilder->fetchAllAssociative() : [];
            
            $stmt = $this->connection->prepare($query);
            $results = $stmt->executeQuery();
            $rows =  $results->fetchAllAssociative();

            return $this->hands->find($rows[0]);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), ['class' => self::class, 'method' => __METHOD__]);

            return null;
        }
    }
}