<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Week extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function competitions()
    {
        return $this->hasMany(Competition::class);
    }

    public function scopeGetLatestWeek()
    {
        return $this
            ->all()
            ->filter(function ($week) {
                return !$week->is_played;
            })
            ->first();
    }

    public function getIsPlayedAttribute()
    {
        return $this->competitions->every(function ($competition) {
            return $competition->is_played;
        });
    }

    public function previousWeek()
    {
        return $this->find($this->where('id', '<', $this->id)->max('id'));
    }
}
