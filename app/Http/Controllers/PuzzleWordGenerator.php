<?php

namespace App\Http\Controllers;

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

	public function crossword(){
		return Word::count();
	}

	private function getRandomWord()
	{
		$file = file(__DIR__ . "/words.txt");
		return $file[array_rand($file)];
	}
}
