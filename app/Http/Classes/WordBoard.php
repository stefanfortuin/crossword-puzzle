<?php

namespace App\Http\Classes;

class WordBoard
{
    public $x;
    public $y;
    public $direction;

    public function __construct($word)
    {
        foreach ($word->toArray() as $key => $value) {
            $this->{$key} = $value;
        }
    }

	public function getLetterCoordinates()
	{
		$coordinates = collect();

		foreach ($this->synonym as $i => $letter) {
			switch ($this->direction) {
				case 'right':
					$coordinates->put($this->x.','.$this->y+$i, $letter);
					break;
				case 'down':
					$coordinates->put($this->x+$i.','.$this->y, $letter);
					break;
			}
		}

		return $coordinates;
	}
}
