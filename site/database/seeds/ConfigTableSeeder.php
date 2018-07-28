<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ConfigTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('config')->insert([
            'name' => 'items_hash',
            'value' => Carbon::now()->timestamp,
        ]);
    }
}
