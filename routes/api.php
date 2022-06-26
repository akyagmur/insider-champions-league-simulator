<?php

use App\Http\Controllers\FixtureController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\LeagueTableController;
use App\Http\Controllers\PredictionController;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('fixtures/get-latest-week',    [FixtureController::class, 'getLatestWeek'])->name('fixtures.get_latest_week');
Route::get('fixtures/get-fixtures',       [FixtureController::class, 'getAllFixtures'])->name('fixtures.get_all_fixtures');
Route::get('fixtures/get-fixture/{week}', [FixtureController::class, 'getFixture'])->name('fixtures.get_fixture');
Route::get('fixtures/prepare-fixtures',   [FixtureController::class, 'prepareFixtures'])->name('fixtures.prepare_fixtures');
Route::get('fixtures/reset-fixtures',     [FixtureController::class, 'resetFixtures'])->name('fixtures.reset_fixtures');
Route::get('play/next-week',              [GameController::class, 'playNextWeek'])->name('play.next_week');
Route::get('play/all-weeks',              [GameController::class, 'playAllWeeks'])->name('play.all_weeks');
