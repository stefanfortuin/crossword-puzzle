<?php

use App\Models\Word;
use App\Models\Synonym;
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

Route::get('/word', function (Request $request) {

	$lettersToCheck = collect($request->input('letters_to_check'));
	$length = $request->input('length');
	$foundSynonym = null;

	Synonym::inRandomOrder()->chunk(500, function ($synonyms) use ($length, $lettersToCheck, &$foundSynonym) {
		foreach ($synonyms as $synonym) {
			if ($length != null && strlen($synonym->synonym) != $length)
				continue;

			$matched = $lettersToCheck->every(function ($letter, $position) use ($synonym) {
				return substr($synonym->synonym, $position, 1) == $letter;
			});


			if ($matched) {
				$foundSynonym = collect([
					'id' => $synonym->id,
					'word' => $synonym->words->random(),
					'synonym' => $synonym->synonym,
				]);
				return false;
			}
		}
	});

	return $foundSynonym;
});
