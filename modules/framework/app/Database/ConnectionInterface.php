<?php

namespace Atsmacode\Framework\Database;

interface ConnectionInterface
{
    public function getConnection();
    public function getDatabaseName(): string;
}