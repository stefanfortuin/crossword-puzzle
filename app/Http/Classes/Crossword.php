<?php

namespace App\Http\Classes;

use App\Models\Word;

class Crossword
{
	public $rows;
	public $cols;
	public $words;

	public $board = [];

	public function __construct($rows, $cols)
	{
		$this->rows = $rows;
		$this->cols = $cols;
		// $this->words = $this->generateWordObjects($words);
		$this->createEmptyBoard();
		// $this->fillFirstWord();

		$foundWord = $this->findSynonymInDatabaseWithLettersAtPositionsWithLength([0 => "A", 3 => "E"], 8);
		// if($foundWord != null)
		// 	dd($foundWord);
	}

	private function placeWordAtPosition($x, $y, $word, $direction)
	{
		$wordLength = strlen($word);

		for ($i = 0; $i < $wordLength; $i++) {
			switch ($direction) {
				case 'right':
					$this->board[$x][$y + $i] = substr($word, $i, 1);
					break;
				case 'down':
					$this->board[$x + $i][$y] = substr($word, $i, 1);
					break;
			}
		}
	}

	private function createEmptyBoard()
	{
		for ($x = 0; $x < $this->rows; $x++) {
			for ($y = 0; $y < $this->cols; $y++) {
				$this->board[$x][$y] = '';
			}
		}
	}

	private function findSynonymInDatabaseWithLettersAtPositionsWithLength($lettersToCheck, $length)
	{
		$lettersToCheck = collect($lettersToCheck);
		$foundWord = null;

		Word::inRandomOrder()->chunk(200, function ($words) use ($length, $lettersToCheck, &$foundWord) {
			foreach ($words as $word) {
				foreach ($word->synonyms as $lengths) {
					foreach ($lengths as $synonym) {
						if (strlen($synonym) != $length)
							continue;

						$matched = $lettersToCheck->every(function ($letter, $position) use ($synonym) {
							return substr($synonym, $position, 1) == $letter;
						});


						if ($matched){
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
	}

	private function generateWordObjects($words)
	{
		$list = collect();

		foreach ($words as $word) {
			$list->add(new Word($word));
		}

		return $list;
	}
}
