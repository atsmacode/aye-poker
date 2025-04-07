<?php

namespace Atsmacode\Framework\Models;

use Atsmacode\Framework\Collection\Collection;

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

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setTestDesc(string $testDesc): void
    {
        $this->test_desc = $testDesc;
    }

    public function getTestDesc(): string
    {
        return $this->test_desc;
    }
}
