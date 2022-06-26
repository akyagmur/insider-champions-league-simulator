<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
    use HasFactory;

    const TEAM_STRENGTH = 0.7;
    const HOST_ADVANTAGE = 0.15;
    const LEAGUE_ADVANTAGE = 0.15;

    protected $fillable = [
        'week_id',
        'host_team_id',
        'guest_team_id',
        'host_team_score',
        'guest_team_score'
    ];

    protected $appends = ['is_played'];

    public function week()
    {
        return $this->belongsTo(Week::class);
    }

    public function hostTeam()
    {
        return $this->hasOne(Team::class, 'id', 'host_team_id');
    }

    public function guestTeam()
    {
        return $this->hasOne(Team::class, 'id', 'guest_team_id');
    }

    public function getIsPlayedAttribute()
    {
        return !is_null($this->host_team_score) && !is_null($this->guest_team_score);
    }
}
