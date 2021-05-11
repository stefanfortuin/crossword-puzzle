<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Synonym extends Model
{
    use HasFactory;

	protected $guarded = [];

	protected $hidden = [
		'created_at',
		'updated_at'
	];

	public function getLetterAmountAttirubte()
	{
		return strlen($this->synonym);
	}

	public function words()
	{
		return $this->belongsToMany(Word::class, 'synonym_word');
	}
}
