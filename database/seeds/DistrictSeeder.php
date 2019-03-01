<?php

use Illuminate\Database\Seeder;
use Autodrive\Repositories\Address;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Address::seeder('District');
    }
}
