<?php

namespace App\Http\Controllers;

use App\Exceptions\CompetitionAlreadyCompletedException;
use App\Models\Competition;
use App\Models\Team;

trait GameTrait
{
    /**
     * Play the given competition.
     *
     * @param Competition $competition
     * @return void
     */
    public function playGame(Competition $competition): Competition
    {
        // Check if the competition has already been completed
        if ($competition->is_played) {
            throw new CompetitionAlreadyCompletedException();
        }

        return $this->calculateScore($competition);
    }

    /**
     * Calculate win rate of team by determinants.
     *
     * @param Competition $competition
     * @return float
     */
    public function calculateWinRate(Competition $competition, Team $team): float
    {
        $isTeamHost = $competition->host_team_id === $team->id;

        if ($isTeamHost) {
            $otherTeam = $competition->guestTeam;
        } else {
            $otherTeam = $competition->hostTeam;
        }

        // If the host team has more points, the league advantage is random between 1 and 100
        $leagueAdvantage = $team->table->sum('points') > $otherTeam->table->sum('points') ? rand(1, 10) : 0;

        // If the team is host, give some advantage
        $hostAdvantage = $isTeamHost ? rand(1, 5) : 0;

        $winRate = ($team->strength)
            + ($hostAdvantage)
            + ($leagueAdvantage);

        return $winRate;
    }

    private function teamStrengthByWinRate($winRate)
    {
        return match (true) {
            ($winRate >= 70) => 3,
            ($winRate >= 40) => 2,
            ($winRate < 40)  => 1,
        };
    }

    /**
     * Calculate the score of the competition.
     *
     * @param Competition $competition
     * @return Competition
     */
    public function calculateScore(Competition $competition): Competition
    {
        $hostPoints = $this->calculateWinRate($competition, $competition->hostTeam);
        $guestPoints = $this->calculateWinRate($competition, $competition->guestTeam);

        $hostWinRate = $hostPoints / ($hostPoints + $guestPoints);
        $guestWinRate = $guestPoints / ($hostPoints + $guestPoints);

        $hostTeamStrength = $this->teamStrengthByWinRate($hostWinRate);
        $guestTeamStrength = $this->teamStrengthByWinRate($guestWinRate);

        $hostGoals = $this->scoreGoals($hostTeamStrength);
        $guestGoals = $this->scoreGoals($guestTeamStrength);

        $competition->host_team_score += $hostGoals;
        $competition->guest_team_score += $guestGoals;

        $competition->save();

        $this->updateLeagueTable($competition);

        return $competition;
    }

    /**
     * Check if is a goal made by the team.
     *
     * @param int|float $winRate
     * @return int
     */
    public function scoreGoals(int $teamStrength): int
    {
        $poissonDistribution = [
            1 => [0.36788, 0.73576, 0.9197, 0.98101, 0.98408],
            2 => [0.13534, 0.40601, 0.67668, 0.85713, 0.94735, 0.98344, 0.99547, 0.99891, 0.99977],
            3 => [0.04979, 0.19915, 0.42319, 0.64723, 0.81526, 0.91608, 0.96649, 0.96865, 0.97675, 0.97945, 0.98026],
        ];

        $distribution = $poissonDistribution[$teamStrength];
        $random = rand(0, 100000) / 100000;

        $goals = 0;
        foreach ($distribution as $goal => $probability) {
            if ($random <= $probability) {
                $goals = $goal;
                break;
            }
        }

        return $goals;
    }

    /**
     * Update league table.
     *
     * @param Competition $competition
     * @return void
     */
    public function updateLeagueTable(Competition $competition): void
    {
        $isDraw = $competition->host_team_score === $competition->guest_team_score;

        $hostPoint = $competition->host_team_score > $competition->guest_team_score ? 3 : ($isDraw ? 1 : 0);
        $guestPoint = $competition->guest_team_score > $competition->host_team_score ? 3 : ($isDraw ? 1 : 0);

        $competition->hostTeam->table()->update([
            'points'          => $competition->hostTeam->table->points + $hostPoint,
            'played'          => $competition->hostTeam->table->played + 1,
            'won'             => $competition->hostTeam->table->won + ($competition->host_team_score > $competition->guest_team_score ? 1 : 0),
            'draw'            => $competition->hostTeam->table->draw + ($isDraw ? 1 : 0),
            'lost'            => $competition->hostTeam->table->lost + ($competition->host_team_score < $competition->guest_team_score ? 1 : 0),
            'goal_difference' => $competition->hostTeam->table->goal_difference + ($competition->host_team_score - $competition->guest_team_score),
        ]);

        $competition->guestTeam->table()->update([
            'points'          => $competition->guestTeam->table->points + $guestPoint,
            'played'          => $competition->guestTeam->table->played + 1,
            'won'             => $competition->guestTeam->table->won + ($competition->guest_team_score > $competition->host_team_score ? 1 : 0),
            'draw'            => $competition->guestTeam->table->draw + ($isDraw ? 1 : 0),
            'lost'            => $competition->guestTeam->table->lost + ($competition->guest_team_score < $competition->host_team_score ? 1 : 0),
            'goal_difference' => $competition->guestTeam->table->goal_difference + ($competition->guest_team_score - $competition->host_team_score),
        ]);
    }
}
