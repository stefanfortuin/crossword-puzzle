<?php

namespace App\Console\Commands;

use App\Http\Controllers\PuzzleWordGenerator;
use Illuminate\Console\Command;

class GenerateWords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'words:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the puzzle words';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $controller = new PuzzleWordGenerator();
		$controller->generate();
    }
}
