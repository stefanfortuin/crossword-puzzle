<?php

namespace App\Http\Classes;

class Word
{
    public $word;
    public $synonyms;

    public $intersections;
    public $startPoint;
    public $chosenSynonym;

    public $row;
    public $col;
    public $direction;


    public function __construct($word)
    {
        foreach ($word->toArray() as $key => $value) {
            $this->{$key} = $value;
        }
    }

    public function findSynonymWithLettersAtPositionsWithLength($maxLength, $lettersToCheck)
    {
        $lettersToCheck = collect($lettersToCheck);

        foreach ($this->synonyms as $length) {
            foreach ($length as $synonym) {
                if(strlen($synonym) > $maxLength)
                    continue;

                $matched = $lettersToCheck->every(function($letter, $position) use ($synonym) {
                    return substr($synonym, $position, 1) == $letter;
                });

                if($matched)
                    return collect([
                        'word' => $this->word,
                        'synonym' => $synonym,
                    ]);
            }
        }

        return null;
    }
}
