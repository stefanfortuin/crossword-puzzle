<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WordResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
			'word' => $this->word,
			'synonym_amount' => $this->synonym_amount,
			'synonyms' => $this->synonyms,
		];
    }
}
