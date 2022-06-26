<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'name',
        'code',
        'country',
        'founded',
        'national',
        'strength',
        'logo'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->strength = rand(1, 10);
        });

        static::created(function ($model) {
            $model->table()->create();
        });
    }

    public function hostCompetitions()
    {
        return $this->hasMany(Competition::class, 'host_team_id', 'id');
    }

    public function guestCompetitions()
    {
        return $this->hasMany(Competition::class, 'guest_team_id', 'id');
    }

    public function getCompetitionsAttribute()
    {
        return $this->hostCompetitions->merge($this->guestCompetitions);
    }

    public function getNormalizedStrengthAttribute()
    {
        return $this->strength / 10;
    }

    public function table()
    {
        return $this->hasOne(LeagueTable::class, 'team_id', 'id');
    }
}
