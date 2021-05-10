<?php 

namespace App\Http\Classes;

class Crossword {
    public $rows;
    public $cols;
    public $words;

    public $board = [];

    public function __construct($rows, $cols, $words) {
        $this->rows = $rows;
        $this->cols = $cols;
        $this->words = $this->generateWordObjects($words);
        $this->createEmptyBoard();
        foreach ($this->words as $word) {
            $foundWord = $word->findSynonymWithLettersAtPositionsWithLength(8, ["0" => "A", "2" => "R"]);
            if($foundWord != null)
                dd($foundWord);
        }
    }

    private function placeWordAtPosition($x, $y, $word, $direction)
    {
        $wordLength = strlen($word);

        for ($i=0; $i < $wordLength; $i++) { 
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
		for ($x=0; $x < $this->rows; $x++) { 
			for ($y=0; $y < $this->cols; $y++) { 
				$this->board[$x][$y] = '';
			}
		}
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