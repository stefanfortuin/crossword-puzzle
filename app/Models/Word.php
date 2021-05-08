<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Word extends Model
{
    use HasFactory;

	protected $guarded = [];

	protected $hidden = [
		'created_at',
		'updated_at'
	];

	protected $appends = [
		'synonym_amount',
	];

	protected $casts = [
		'synonyms' => 'array',
	];

	public function getSynonymAmountAttribute()
	{
		return count($this->synonyms);
	}

}
