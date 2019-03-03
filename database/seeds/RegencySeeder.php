<?php

use Illuminate\Database\Seeder;
use Autodrive\Repositories\Address;

class RegencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Address $address)
    {
        $address->seeder('Regency');
    }
}
