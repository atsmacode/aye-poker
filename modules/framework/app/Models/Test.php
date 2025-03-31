<?php

namespace Atsmacode\Framework\Models;

use Atsmacode\Framework\Collection\Collection;
use Atsmacode\Framework\Dbal\Model;

class Test extends Model
{
    use Collection;

    protected string $table = 'test';
    private string $name;
    private string $test_desc;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTestDesc(): string
    {
        return $this->test_desc;
    }
}
