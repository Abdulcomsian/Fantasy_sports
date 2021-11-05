<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
	return view('welcome');
});

Route::get('league/join', 'LeagueController@joinLeague');
Route::get('league/team', 'LeagueController@getteam');
Route::group(['middleware' => ['auth']], function () {
	Route::get('draft-roaster', function () {
		return view('welcome');
	});
	// Route::get('/home', 'HomeController@index');
	Route::get('/home', 'HomeController@new_index');
	Route::get('/completed-league', 'HomeController@completed_league');
	Route::get('/renew/league', 'HomeController@renew_league');
	Route::post('/renew-league', 'HomeController@renew_league_save');
	Route::get('/account/edit', 'HomeController@accountedit')->name('editprofile');
	Route::post('/account/update', 'HomeController@accountupdate')->name('updateprofile');
	Route::prefix('league')->group(function () {

		Route::post('join/key', 'HomeController@fetchLeagueInfo');
		Route::get('{id}/teams', 'LeagueController@teams');
		Route::post('{id}/teams', 'LeagueController@updateTeams');
		Route::get('{id}/settings', 'LeagueController@settings');
		Route::post('{id}/changeStatus', 'LeagueController@changeStatus');
		Route::get('{id}/rounds', 'LeagueController@rounds');
		Route::post('{id}/rounds', 'LeagueController@updateRoundOrder');
		Route::post('{id}/settings', 'LeagueController@updateSettings');
		Route::post('{id}/teams/delete', 'LeagueController@deleteTeam');
		Route::post('{id}/join', 'LeagueController@joinLeagueTeam');
		Route::post('{id}/commish/save', 'LeagueController@saveCommish');
		Route::get('{id}/order', 'LeagueController@assignOrder');
		Route::post('{id}/order', 'LeagueController@updateOrder');
		Route::get('{id}/draft', 'DraftController@index');
		Route::post('{id}/savePick', 'DraftController@savePick');
		Route::post('{id}/draft/pick/delete/{round_id}', 'DraftController@deletePick');
		Route::post('{id}/draft/timer/save', 'DraftController@saveTimer');
		Route::post('{id}/draft/timer/{type}', 'DraftController@timerSettings');
		Route::post('{id}/saveroundkeeperlist', 'DraftController@saveroundkeeperlist');
		Route::post('{id}/updateroundkeeperlist', 'DraftController@updateroundkeeperlist');
		Route::post('{id}/savekeeperlist', 'DraftController@savekeeperlist');
		Route::post('{id}/removekeeperlist', 'DraftController@removekeeperlist');
		Route::get('{id}/updatekeeperlist', 'DraftController@updatekeeperlist');
		Route::get('{id}/movekeeperlist', 'DraftController@movekeeperlist');
		Route::post('{id}/get-round-order', 'DraftController@get_round_order')->name("get-round-order");
		Route::post('{id}/save-roster', 'LeagueController@save_roster')->name("save-roster");
		Route::get('{id}/roster-view', 'DraftController@roster_view')->name("roster-view");
		Route::post('{id}/insertrow', 'LeagueController@insertrow')->name('insertrow');
		Route::post('{id}/deleterow', 'LeagueController@deleterow')->name('deleterow');
		Route::post('{id}/addcolor', 'LeagueController@addcolor')->name('addcolor');
		//my new work obaid

		Route::get('{id}/squads', 'SquadController@index');
		Route::get('{id}/team/{team_id}/players', 'SquadController@teamPlayers');
		Route::post('{id}/keeper/cost', 'SquadController@saveKeeperCost');
		Route::get('{id}/trading', 'TradeController@index');
	});
	Route::get('/changeTeam', 'DraftController@changeTeam')->name('changeTeam');
	Route::get('/removePlayer', 'DraftController@removePlayer')->name('removePlayer');
	Route::resource('league', LeagueController::class);

	Route::get('yahon/index', 'YahooController@index');
	Route::get('yahon/apiCheck', 'YahooController@apiCheck');
});

Route::get('/contact', function () {
	return view('contact');
});
Route::get('/about', function () {
	return view('about-us');
});
