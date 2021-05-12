<?php

use App\Models\Word;
use App\Models\Synonym;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

Route::get('/word', function (Request $request) {

	$lettersToCheck = collect($request->input('letters_to_check'));
	$length = $request->input('length');
	$foundSynonym = null;

	$whereClausesLetters = $lettersToCheck->map(function ($letter, $position) {
		return [DB::raw("SUBSTR(synonym, ".$position.",1)"), '=', $letter];
	});

	$whereClausesLetters->add([DB::raw("LENGTH(synonym)"), '=', $length]);

	$foundSynonym = Synonym::inRandomOrder()->where($whereClausesLetters->toArray())->with('words')->get();

	return $foundSynonym;
});
