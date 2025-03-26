<?php

namespace Atsmacode\PokerGame\Models;

use Atsmacode\Framework\Dbal\Model;
class PlayerActionLog extends Model
{
    protected string $table = 'player_action_logs';
    private int      $player_status_id;
    private int      $player_action_id;
    private ?int     $bet_amount;
    private bool     $big_blind;
    private bool     $small_blind;
    private int      $player_id;
    private int      $action_id;
    private int      $hand_id;
    private int      $hand_street_id;
    private int      $table_seat_id;
    private string   $created_at;
}
