<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeagueTable extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'points',
        'played',
        'won',
        'draw',
        'lost',
        'goal_difference',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
