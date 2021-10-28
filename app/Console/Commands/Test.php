<?php

namespace App\Console\Commands;

use App\Services\API\NumbersApi\NumbersApiClient;
use Illuminate\Console\Command;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $numberApiClient = new NumbersApiClient();

        dump($numberApiClient->getNewTriviaQuestion());
        dump($numberApiClient->getNewDateQuestion());
        dump($numberApiClient->getNewMathQuestion());
        dump($numberApiClient->getNewYearQuestion());
        dump($numberApiClient->getNewRandomTypeQuestion());
    }
}
