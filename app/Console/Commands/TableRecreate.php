<?php

namespace Autodrive\Console\Commands;

use Illuminate\Console\Command;

class TableRecreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'table:recreate';

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
    public function handle()
    {
        //
        $this->call('migrate:refresh', [
            '--path' => 'database/migrations/2019_02_26_065059_create_members_table.php'
        ]);
        $this->call('migrate:refresh', [
            '--path' => 'database/migrations/2019_02_26_064924_create_purchases_table.php'
        ]);
        $this->call('migrate:refresh', [
            '--path' => 'database/migrations/2014_10_12_000000_create_users_table.php'
        ]);
    }
}
