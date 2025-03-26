<?php

namespace Atsmacode\PokerGame\Models;

use Atsmacode\Framework\Dbal\Model;

class Action extends Model
{
    protected string $table = 'actions';
    private string   $name;
}
