<?php

namespace Atsmacode\Framework\Migrations;

use Atsmacode\Framework\Database\Database;

class CreateTestTable extends Database
{
    public static array $methods = [
        'createTestTable',
    ];

    public function createTestTable(): void
    {
        $sql = '
                CREATE TABLE test (
                    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(30) NOT NULL,
                    test_desc VARCHAR(30) NOT NULL
                )
            ';

        try {
            $this->connection->executeQuery($sql);
        } catch (\Exception $e) {
            error_log($e->getMessage());
        }
    }
}
