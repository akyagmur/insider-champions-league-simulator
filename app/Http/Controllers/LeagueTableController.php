<?php

namespace App\Http\Controllers;

use App\Models\LeagueTable;

class LeagueTableController extends Controller
{
    public function getLeagueTable()
    {
        return LeagueTable::with('team')->get();
    }
}
