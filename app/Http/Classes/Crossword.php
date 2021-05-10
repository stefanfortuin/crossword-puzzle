<?php

namespace App\Http\Classes;

use App\Models\Word;
use App\Http\Classes\WordBoard;

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
		$this->createEmptyBoard();

		$this->fillBoard();
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

	private function fillBoard()
	{
		$firstWord = Word::inRandomOrder()->first();
		$word = new WordBoard($firstWord);
		$this->placeWordAtPosition(0,0,$word->longest_synonym, 'right');

		for ($i=0; $i < $this->cols; $i++) { 
			if($i % 2 == 0){
				
			}
		}

	}

	private function getLettersAtPositionInCol($col)
	{
		$letters = collect();

		for ($i=0; $i < $this->rows; $i++) { 
			$letter = $this->board[$i][$col];
			$letters->put($i, $letter);
		}

		return $letters;
	}

	private function getLettersAtPositionInRow($row)
	{
		$letters = collect();

		for ($i=0; $i < $this->cols; $i++) { 
			$letter = $this->board[$row][$i];
			$letters->put($i, $letter);
		}

		return $letters;
	}

	private function findSynonymInDatabaseWithLettersAtPositionsWithLength($lettersToCheck, $length = null)
	{
		$lettersToCheck = collect($lettersToCheck);
		$foundWord = null;

		Word::inRandomOrder()->chunk(5000, function ($words) use ($length, $lettersToCheck, &$foundWord) {
			foreach ($words as $word) {
				foreach ($word->synonyms as $lengths) {
					foreach ($lengths as $synonym) {
						if ($length != null && strlen($synonym) != $length)
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
