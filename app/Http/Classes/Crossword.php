<?php

namespace App\Http\Classes;

use App\Models\Word;
use App\Models\Synonym;
use App\Http\Classes\WordBoard;
use Illuminate\Support\Facades\DB;

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
		$this->words = collect();
		$this->createBoard();
		$this->startCoordinates = $this->getAllWordStartCoordinates();

		// $this->generate();
	}

	private function placeWordAtPosition($x, $y, $word, $direction)
	{
		$wordLength = strlen($word->synonym);

		$word->x = $x;
		$word->y = $y;
		$word->direction = $direction;

		for ($i = 0; $i < $wordLength; $i++) {
			switch ($direction) {
				case 'right':
					$this->board[$x][$y + $i] = substr($word->synonym, $i, 1);
					break;
				case 'down':
					$this->board[$x + $i][$y] = substr($word->synonym, $i, 1);
					break;
			}
		}
	}

	private function createBoard()
	{
		for ($x = 0; $x < $this->rows; $x++) {
			for ($y = 0; $y < $this->cols; $y++) {
				if (mt_rand(0,99) < 25)
					$this->board[$x][$y] = '#';
				else
					$this->board[$x][$y] = '-';
			}
		}
	}

	private function generate()
	{
		//place the first word on the board
		$firstWord = Synonym::inRandomOrder()->first();
		$word = new WordBoard($firstWord);
		$this->placeWordAtPosition(0, 0, $word, 'right');

		//

	
	}

	private function getAllWordStartCoordinates()
	{
		$coordinates = collect();

		for ($x = 0; $x < $this->rows; $x++) {
			for ($y = 0; $y < $this->cols; $y++) {
					if ($this->board[$x][$y] == '#')
					{
						$this->board[$x+1][$y] = '@';
						$this->board[$x][$y+1] = '@';
					}
			}
		}

		return $coordinates;
	}

	private function getLettersAtPositionInCol($col)
	{
		$letters = collect();

		for ($i = 0; $i < $this->rows; $i++) {
			$letter = $this->board[$i][$col];
			$letters->put($i, $letter);
		}

		return $letters;
	}

	private function getLettersAtPositionInRow($row)
	{
		$letters = collect();

		for ($i = 0; $i < $this->cols; $i++) {
			$letter = $this->board[$row][$i];
			$letters->put($i, $letter);
		}

		return $letters;
	}

	private function findSynonymInDatabaseWithLettersAtPositionsWithLength($lettersToCheck, $length = null)
	{
		$lettersToCheck = collect($lettersToCheck);
		$foundSynonym = null;

		$whereClausesLetters = $lettersToCheck->map(function ($letter, $position) {
			return [DB::raw("SUBSTR(synonym, ".$position.",1)"), '=', $letter];
		});
	
		$whereClausesLetters->add([DB::raw("LENGTH(synonym)"), '=', $length]);
	
		$foundSynonym = Synonym::inRandomOrder()->where($whereClausesLetters->toArray())->with('words')->get();

		return $foundSynonym;
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
