<?php

namespace App\Jobs;

use App\Models\Word;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class PuzzleWordJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	public $word;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($word)
    {
        $this->word = $word;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $response = Http::get('https://www.mijnwoordenboek.nl/puzzelwoordenboek/'.$this->word.'/1/1');
		
		$body = str_replace("\n", "",$response->body());
		$body = str_replace("\r", "",$body);
		$body = str_replace("\t", "",$body);

		$dom = new \DOMDocument();
		@$dom->loadHTML(mb_convert_encoding($body, 'HTML-ENTITIES', 'UTF-8'));

		$rows = $dom->getElementsByTagName('tr');
		if(count($rows) == 0){
			return;
		}

		$words = collect();
		$current_letter_amount = null;

		foreach ($rows as $index => $row) {
			if($index % 2 == 0){
				preg_match("/(?P<number>\d+) letters/", $row->textContent, $matches);
				$current_letter_amount = $matches['number'];
			}
			else{
				$exploded_words = explode(" ", $row->textContent);
				$words->put($current_letter_amount, $exploded_words);
			}
		}

		Word::create([
			'word' => $this->word,
			'synonyms' => $words,
		]);

    }
}
