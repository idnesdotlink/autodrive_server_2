<?php

namespace Autodrive\Console\Commands;

use Illuminate\Console\Command;
use Autodrive\Repositories\Scenario;

class ScenarioGenerate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scenario:generate';

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
     * @return mixed
     */
    public function handle(Scenario $scenario)
    {
        //
        print_r(json_encode($scenario->level()[1]['name']));
    }
}
