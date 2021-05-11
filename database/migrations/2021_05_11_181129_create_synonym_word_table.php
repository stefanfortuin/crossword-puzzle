<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSynonymWordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('synonym_word', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('synonym_id');
			$table->unsignedBigInteger('word_id');
            $table->timestamps();

			$table->foreign('synonym_id')->references('id')->on('synonyms');
			$table->foreign('word_id')->references('id')->on('words');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('synonym_word');
    }
}
