<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\LeagueTable;
use App\Models\Team;

trait FixtureTrait
{
    /**
     * Prepare the fixture for the given competition.
     *
     * @param Competition $competition
     * @return void
     */
    private function prepareAllFixtures(): void
    {
        Competition::truncate();   // Clear all competitions
        $this->clearLeagueTable(); // Clear all score board

        // Select 4 random teams from the database
        $teams = Team::all();

        /**
         * Get all teams and cross join them with themselves in order to create all possible combinations of teams.
         * Then, filter out the combinations for which the teams are the same.
         * Finally, clear duplicate combinations from the resulting array to determine first and second halves of the fixture.
         */
        $competitions = $teams
            ->crossJoin($teams)
            ->filter(function ($competition) {
                return $competition[0]['team_id'] !== $competition[1]['team_id'];
            })
            ->map(function ($competition) {
                return [
                    $competition[0]['id'],
                    $competition[1]['id'],
                ];
            })
            ->values();

        foreach (range(1, 6) as $i) {
            $ilkmac     = $competitions[$i - 1];
            $ikincimac  = $competitions[count($competitions) - $i];

            Competition::create([
                'host_team_id' => $ilkmac[0],
                'guest_team_id' => $ilkmac[1],
                'week_id' => $i,
            ]);

            Competition::create([
                'host_team_id' => $ikincimac[0],
                'guest_team_id' => $ikincimac[1],
                'week_id' => $i,
            ]);
        }
    }

    /**
     * Clear the league table.
     *
     * @return void
     */
    private function clearLeagueTable(): void
    {
        LeagueTable::each(function ($leagueTable) {
            $leagueTable->update([
                'points' => 0,
                'won' => 0,
                'lost' => 0,
                'draw' => 0,
                'goal_difference' => 0
            ]);
        });
    }
}
