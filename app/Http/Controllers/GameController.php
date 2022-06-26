<?php

namespace App\Http\Controllers;

use App\Exceptions\FixtureAlreadyPlayedException;
use App\Exceptions\PreviousWeekIsNotPlayedException;
use App\Models\Week;
use Illuminate\Http\Request;

class GameController extends Controller
{
    use GameTrait;

    public function playWeek(Week $week)
    {
        if ($week->name !== "Week 1" && !$week->previousWeek()->is_played) {
            throw new PreviousWeekIsNotPlayedException("You can't play this week yet!");
        }

        return $week->competitions->map(function ($competition) {
            return $this->playGame($competition);
        });
    }

    public function playNextWeek()
    {
        $week = Week::getLatestWeek();

        if ($week->is_played) {
            throw new FixtureAlreadyPlayedException();
        }

        return $week->competitions->map(function ($competition) {
            return $this->playGame($competition);
        });
    }

    public function playAllWeeks()
    {
        return Week::all()->map(function ($week) {
            return [
                'week' => $week,
                'competitions' => $week->is_played ? $week->competitions : $this->playWeek($week),
            ];
        });
    }
}
