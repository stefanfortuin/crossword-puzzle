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

	public function synonyms(){
		return $this->belongsToMany(Synonym::class, 'synonym_word');
	}

}
