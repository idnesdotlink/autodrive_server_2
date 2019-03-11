<?php

namespace Autodrive\Console\Commands;

use Illuminate\Console\Command;
use Autodrive\Repositories\MembersTableSeed;
use Illuminate\Support\Facades\{DB, Storage, Hash};
use Autodrive\Models\User;
class ScenarioGenerate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scenario:generate {depth?} {--save} {--no-progress}';

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
    public function handle(MembersTableSeed $membersTableSeed, Storage $storage)
    {

        $depth = (int) $this->arguments()['depth'];
        $this->line($depth);
        $user_table = DB::table('users');
        $member_table = DB::table('members');
        // $user_table->truncate();
        $member_table->truncate();
        $disk = $storage::disk('coba');
        // print_r($disk);
        /* User::create([
            'name'     => 'admin',
            'email'    => 'admin@localhost',
            'password' => Hash::make('12345678'),
        ]); */

        $x = $membersTableSeed->generate_seed($depth);
        $disk->put('test2.json', json_encode($x));
    }
}
