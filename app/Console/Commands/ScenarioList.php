<?php

namespace Autodrive\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\{DB, Storage};

class ScenarioList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scenario:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private $storage;
    private $path;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Storage $storage)
    {
        parent::__construct();
        $this->storage = $storage::disk('scenario');
        $this->path = app_path('data/scenario');
    }

    public function all() {
        return collect($this->storage->files())->map(
            function ($item) {
                return $this->path . '/' . $item;
            }
        );
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        // $pathToScenarios = 'scenario';
        /* if (!$disk->exists($pathToScenarios)) {
            $disk->makeDirectory($pathToScenarios);
        } else {
            $disk->deleteDirectory($pathToScenarios);
            $disk->makeDirectory($pathToScenarios);
        } */
        
        print_r($this->all());
    }
}
