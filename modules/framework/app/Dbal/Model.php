<?php

namespace Atsmacode\Framework\Dbal;

use Atsmacode\Framework\Database\ConnectionInterface;
use Atsmacode\Framework\Database\Database;
use Psr\Log\LoggerInterface;

abstract class Model extends Database
{
    /**
     * @var \ReflectionClass<Model>
     */
    private \ReflectionClass $reflection;

    protected string $table;
    protected int $id;
    protected array $content = [];

    /**
     * @param \ReflectionClass<Model> $reflection
     */
    public function __construct(
        ConnectionInterface $connection,
        LoggerInterface $logger,
        \ReflectionClass $reflection,
    ) {
        parent::__construct($connection, $logger);

        $this->reflection = $reflection;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function exists(): bool
    {
        return isset($this->id);
    }

    public function setContent(array $content): void
    {
        $this->content = $content;
    }

    public function getContent(): array
    {
        return $this->content;
    }

    public function find(?array $data = null): ?self
    {
        $rows = null;
        $properties = $this->compileWhereStatement($data);

        try {
            $stmt = $this->connection->prepare("
                SELECT * FROM {$this->table} {$properties}
            ");

            $results = $stmt->executeQuery();
            $rows = $results->fetchAllAssociative();
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), ['class' => self::class, 'method' => __METHOD__]);
        }

        $clone = clone $this;

        if (!$rows) {
            return $clone;
        }

        $clone->content = $rows;

        $clone->setModelProperties($rows);

        return $clone;

        /*
         * @todo Would like to return $this->build($rows)
         * here. Caused a lot of failing unit tests.
         */
    }

    public function create(?array $data = null): self
    {
        $id = null;
        $insertStatement = $this->compileInsertStatement($data);

        try {
            $stmt = $this->connection->prepare($insertStatement);

            foreach ($data as $column => &$value) {
                $stmt->bindParam($column, $value);
            }

            $stmt->executeQuery();

            $id = $this->connection->lastInsertId();
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), ['class' => self::class, 'method' => __METHOD__]);
        }

        return $this->build(array_merge(['id' => $id], $data));
    }

    public function build(array $data): self
    {
        $this->content = $data;

        $this->setModelProperties([$data]);

        return $this;
    }

    /** To be used to update a single model instance. */
    public function update(array $data): self
    {
        $properties = $this->compileUpdateStatement($data);

        try {
            $stmt = $this->connection->prepare($properties);

            foreach ($data as $column => &$value) {
                if (null !== $value) {
                    $stmt->bindParam(':'.$column, $value);
                }
            }

            $stmt->executeQuery();
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), ['class' => self::class, 'method' => __METHOD__]);
        }

        return $this->refresh();
    }

    /** To be used to update a multiple model instances. */
    public function updateBatch(array $data, ?string $where = null): self
    {
        $properties = $this->compileUpdateBatchStatement($data, $where);

        try {
            $stmt = $this->connection->prepare($properties);

            foreach ($data as $column => &$value) {
                $stmt->bindParam(':'.$column, $value);
            }

            $stmt->executeQuery();
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), ['class' => self::class, 'method' => __METHOD__]);
        }

        return $this;
    }

    public function refresh(): ?self
    {
        $rows = null;

        try {
            $stmt = $this->connection->prepare("
                SELECT * FROM {$this->table} WHERE id = {$this->id}
            ");

            $results = $stmt->executeQuery();
            $rows = $results->fetchAllAssociative();
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), ['class' => self::class, 'method' => __METHOD__]);
        }

        if ($rows) {
            $this->content = $rows;

            $this->setModelProperties($rows);
        }

        return $this;
    }

    public function all(): self
    {
        $rows = null;

        try {
            $stmt = $this->connection->prepare("
                SELECT * FROM {$this->table}
            ");

            $stmt->executeQuery();

            $rows = $stmt->fetchAllAssociative();
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), ['class' => self::class, 'method' => __METHOD__]);
        }

        $this->content = $rows;

        return $this;
    }

    /**
     * @todo Created this to help with setting NULL values if
     * required. The condition if ($value !== null) { causes
     * problem from time to time in the other methods.
     */
    public function setValue(string $column, string $value): ?Model
    {
        $query = "
            UPDATE
                {$this->table}
            SET
                {$column} = :value
            WHERE
                {$this->table}.id = :id
            LIMIT
                1
        ";

        try {
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':value', $value);
            $stmt->executeQuery();

            return $this;
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), ['class' => self::class, 'method' => __METHOD__]);

            return null;
        }
    }

    private function compileUpdateStatement(array $data): string
    {
        $properties = "UPDATE {$this->table} SET ";
        $pointer = 1;

        foreach ($data as $column => $value) {
            if (null !== $value) {
                $properties .= $column.' = :'.$column;

                if ($pointer < count($data)) {
                    $properties .= ', ';
                }
            }

            ++$pointer;
        }

        $properties .= " WHERE id = {$this->id}";

        return $properties;
    }

    private function compileUpdateBatchStatement(array $data, ?string $where = null): string
    {
        $properties = "UPDATE {$this->table} SET ";
        $pointer = 1;

        foreach ($data as $column => $value) {
            $properties .= $column.' = :'.$column;

            if ($pointer < count($data)) {
                $properties .= ', ';
            }

            ++$pointer;
        }

        if ($where) {
            $properties .= " WHERE {$where}";
        }

        return $properties;
    }

    private function compileWhereStatement(array $data): string
    {
        $properties = 'WHERE ';
        $pointer = 1;

        foreach ($data as $column => $value) {
            if (null === $value) {
                $properties .= $column.' IS NULL';
            } elseif (is_int($value)) {
                $properties .= $column.' = '.$value;
            } else {
                $properties .= $column." = '".$value."'";
            }

            if ($pointer < count($data)) {
                $properties .= ' AND ';
            }

            ++$pointer;
        }

        return $properties;
    }

    private function compileInsertStatement(array $data): string
    {
        $properties = "INSERT INTO {$this->table} (";
        $properties = $this->compileColumns($data, $properties);
        $properties .= 'VALUES (';

        reset($data);

        $properties = $this->compileValues($data, $properties);

        return $properties;
    }

    private function compileColumns(array $data, string $properties): string
    {
        $pointer = 1;

        foreach (array_keys($data) as $column) {
            $properties .= $column;

            if ($pointer < count($data)) {
                $properties .= ', ';
            } else {
                $properties .= ') ';
            }

            ++$pointer;
        }

        return $properties;
    }

    private function compileValues(array $data, string $properties): string
    {
        $pointer = 1;

        foreach (array_keys($data) as $column) {
            $properties .= ':'.$column;

            if ($pointer < count($data)) {
                $properties .= ', ';
            } else {
                $properties .= ')';
            }

            ++$pointer;
        }

        return $properties;
    }

    /** Uses reflection to set private properties of child Model class */
    protected function setModelProperties(array $result): void
    {
        if (1 === count($result)) {
            foreach (array_shift($result) as $column => $value) {
                if ($this->reflection->hasProperty($column)) {
                    $property = $this->reflection->getProperty($column);
                    $property->setValue($this, $value);
                }
            }
        }
    }

    public function isNotEmpty(): bool
    {
        return count($this->content) > 0;
    }

    public function contains(array $data): bool
    {
        return in_array($data, $this->content);
    }
}
