<?php

namespace App\Http\Controllers;

use App\Models\LeagueTable;
use App\Models\Team;
use App\Models\Week;

trait PredictionTrait
{
    public function predictChampion()
    {
        // Get last week which is not played yet
        $lastWeek = Week::with('competitions.hostTeam', 'competitions.guestTeam')
            ->get()
            ->filter(function ($week) {
                return $week->is_played || $week->name === 'Week 1';
            })->last();

        $weeksRemaining = 6 - $lastWeek->id;

        // If league is finished or not started yet, return empty array
        if ($weeksRemaining === 0 || ($lastWeek->name === 'Week 1' && !$lastWeek->is_played)) {
            return [];
        }

        $teams = Team::all();
        $pointsOfLeader = LeagueTable::max('points');

        $predictions = [];
        foreach ($teams as $team) {
            /**
             * If remaining weeks are not enough for a team to become champion, then exclude it from predictions
             * For this purpose, we calculate remaining competitions and maximum available points it can get
             */
            $remainingCompetitions = $team->competitions->filter(fn ($competition) => $competition->is_played === false)->count();
            $maxPossiblePoints = $remainingCompetitions * 3;

            // Calculate a chance score for each team, weight of points is higher goal difference
            $chanceScore =  $team->table->points * 2 + $team->table->goal_difference;
            $predictions[] = [
                'team' => $team->name,
                'max_available_points' => $maxPossiblePoints,
                'chance_of_championship' => $pointsOfLeader > $maxPossiblePoints + $team->table->points ? 0 : 1,
                'chance_score' => $chanceScore > 0 ? $chanceScore : 0,
            ];
        }

        // Sum chance score in order to normalize teams championship rate, exclude teams which has no chance of championship
        $totalChance = collect($predictions)
            ->filter(fn ($prediction) => $prediction['chance_of_championship'] === 1)
            ->sum('chance_score');

        return collect($predictions)
            ->map(function ($row) use ($totalChance) {

                // If team does not have chance of championship, then return 0
                $chance = $row['chance_of_championship'] == 1 ? $row['chance_score'] : 0;

                return [
                    'name' => $row['team'],
                    'probability' =>  number_format(round($chance / $totalChance, 2) * 100, 0),
                ];
            })
            ->values();
    }
}
