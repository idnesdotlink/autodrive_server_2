<?php

use Illuminate\Database\Seeder;
use Autodrive\Repositories\Address;

class VillageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Address::seeder('Village');
    }
}
