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
            ->shuffle()
            ->values();

        $matches = $this->setFirstHalfFixture($competitions->toArray());

        foreach ($matches as $key => $week) {
            foreach ($week as $match) {
                // Create first half of the season
                Competition::create([
                    'week_id' => $key + 1,
                    'host_team_id' => $match[0],
                    'guest_team_id' => $match[1],
                ]);

                // Create second half of the season, inverse of the first half
                Competition::create([
                    'week_id' => ($key + 4),
                    'host_team_id' => $match[1],
                    'guest_team_id' => $match[0],
                ]);
            }
        }
    }

    /**
     * All teams should play in each week.
     */
    private function setFirstHalfFixture(array $array): array
    {
        $combinations = [];
        foreach ($array as $key => $value) {
            foreach ($array as $otherKey => $otherValue) {
                if ($otherKey !== $key) {
                    if (empty(array_intersect($value, $otherValue))) {
                        unset($array[$key]);
                        $combinations[] = [$value, $otherValue];
                        break;
                    }
                }
            }
            if (count($combinations) == 3) break;
        }

        return $combinations;
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
