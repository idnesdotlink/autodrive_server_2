<?php

namespace Autodrive\Console\Commands;

use Illuminate\Console\Command;
use Autodrive\Test\CreateUserMember;

class DummyTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dummy:test';

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
    public function handle(CreateUserMember $create_user_member)
    {
        //
        // $x = $create_user_member->create_user()->first();
        $this->line('test');
    }
}
