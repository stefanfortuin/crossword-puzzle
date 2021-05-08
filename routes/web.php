<?php

use App\Models\Word;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PuzzleWordGenerator;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/words', function (){
	return Word::count();
});

Route::get('/random/{amount}', function ($amount){
	return Word::inRandomOrder()->limit($amount)->get();
});

Route::get('/crossword', [PuzzleWordGenerator::class, 'crossword']);
