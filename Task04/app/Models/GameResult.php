<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameResult extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'game_results';

    protected $fillable = [
        'player_name',
        'correct',
        'missing_number',
        'progression'
    ];
}
