<?php

use App\Models\Word;
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
	$foundWord = null;

	Word::inRandomOrder()->chunk(100, function ($words) use ($length, $lettersToCheck, &$foundWord) {
		foreach ($words as $word) {
			foreach ($word->synonyms as $lengths) {
				foreach ($lengths as $synonym) {
					if (strlen($synonym) != $length)
						continue;

					$matched = $lettersToCheck->every(function ($letter, $position) use ($synonym) {
						return substr($synonym, $position, 1) == $letter;
					});


					if ($matched) {
						$foundWord = collect([
							'id' => $word->id,
							'word' => $word->word,
							'synonym' => $synonym,
						]);
						return false;
					}
				}
			}
		}
	});

	return $foundWord;
});
