<?php

namespace App\Http\Controllers;

use App\Exceptions\FixtureAlreadyPlayedException;
use App\Models\Competition;
use App\Models\Week;
use Illuminate\Http\Request;

class FixtureController extends Controller
{
    use FixtureTrait;

    /**
     * Return a single week with competitions.
     *
     * @return Week
     */
    public function getFixture(Week $week)
    {
        $week->load('competitions.hostTeam', 'competitions.guestTeam');

        return $week;
    }

    /**
     * Return weeks with competitions.
     *
     * @return Week
     */
    public function getAllFixtures()
    {
        return Week::with('competitions.hostTeam', 'competitions.guestTeam')->get();
    }

    /**
     * Re-create all fixtures.
     *
     * @return Collection
     */
    public function resetFixtures()
    {
        $this->prepareAllFixtures();

        return Competition::all();
    }

    /**
     * Prepare all fixtures if not created.
     *
     * @return Collection
     */
    public function prepareFixtures()
    {
        if (Competition::count() > 0) {
            throw new FixtureAlreadyPlayedException();
        }

        $this->prepareAllFixtures();

        return Competition::all();
    }

    /**
     * Return latest week's fixtures.
     *
     * @return Week
     */
    public function getLatestWeek()
    {
        return Week::with('competitions.hostTeam', 'competitions.guestTeam')
            ->get()
            ->filter(function ($week) {
                return $week->is_played || $week->name === 'Week 1';
            })->last();
    }
}
