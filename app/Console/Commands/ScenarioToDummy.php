<?php

namespace Autodrive\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\{DB, Storage, Hash};

class ScenarioToDummy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scn:dm';

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
    public function handle(Storage $storage)
    {
        //
        $disk = $storage::disk('coba');
        $data = $disk->get('test2.json');
        $data = json_decode($data);
        // $data = collect($data);
        $rows= (array) $data->rows;
        $rows = collect($rows);
        print_r($rows->first());
    }
}
