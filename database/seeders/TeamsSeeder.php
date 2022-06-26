<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class TeamsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $teams = $this->getPremierLeagueTeams();
        $teams = collect($teams)->shuffle()->take(4);

        foreach ($teams as $team) {

            $teamData = collect($team['team']);
            Team::updateOrCreate(
                [
                    'team_id' => $teamData->get('id'),
                ],
                [
                    'name' => $teamData->get('name'),
                    'code' => $teamData->get('code'),
                    'country' => $teamData->get('country'),
                    'founded' => $teamData->get('founded'),
                    'national' => $teamData->get('national'),
                    'logo' => $teamData->get('logo'),
                ]
            );
        }
    }

    private function getPremierLeagueTeams(): array
    {
        return Http::withHeaders([
            'X-RapidAPI-Key' => env('RAPID_API_KEY')
        ])
            ->get('https://api-football-v1.p.rapidapi.com/v3/teams', [
                'league' => 39,
                'season' => 2022
            ])
            ->json()['response'];
    }
}
