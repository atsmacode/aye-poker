<?php

namespace Atsmacode\PokerGame\Models;

use Atsmacode\Framework\Dbal\Model;

class Street extends Model
{
    protected string $table = 'streets';
    private string   $name;
}
