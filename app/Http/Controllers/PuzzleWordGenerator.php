<?php

namespace App\Http\Controllers;

use App\Http\Classes\Crossword;
use App\Jobs\PuzzleWordJob;
use App\Models\Word;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PuzzleWordGenerator extends Controller
{
    public function generate()
	{
		$file = fopen(__DIR__ . "/words.txt", "r");
		if ($file) {
			while (($line = fgets($file)) !== false) {
				$word = str_replace("\n", "",$line);
				PuzzleWordJob::dispatch($word);
			}
			fclose($file);
		} else {
			print("could not open dictionary");
		}
		

	}

	public function crossword($word_amount){
		$words = Word::inRandomOrder()->limit($word_amount)->get();

		$crossword = new Crossword(25, 25, $words);
		return $crossword->board;
	}
}
