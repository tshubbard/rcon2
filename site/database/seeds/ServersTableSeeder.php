<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ServersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('servers')->insert([
            'account_id' => 1,
            'name' => 'Rust Playground (For Reals)',
            'host' => '198.24.176.186',
            'password' => 'ruvgahux',
            'port' => '28017',
            'timezone' => 'America/New_York',
            'max_players' => 75,
            'disabled' => 0,
            'order' => 0,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('servers')->insert([
            'account_id' => 1,
            'name' => 'Rust Playground 2',
            'host' => '198.24.176.186',
            'password' => 'DPBn2mh0',
            'port' => '28016',
            'timezone' => 'America/New_York',
            'max_players' => 50,
            'disabled' => 1,
            'order' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('servers')->insert([
            'account_id' => 1,
            'name' => 'Rust Playground 3',
            'host' => '198.24.176.186',
            'password' => 'DPBn2mh0',
            'port' => '28015',
            'timezone' => 'America/New_York',
            'max_players' => 100,
            'disabled' => 1,
            'order' => 2,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('servers')->insert([
            'account_id' => 2,
            'name' => 'Starting to Rust',
            'host' => '64.38.249.114',
            'password' => 'blorp',
            'port' => '28015',
            'timezone' => 'America/New_York',
            'max_players' => 75,
            'disabled' => 1,
            'order' => 0,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('servers')->insert([
            'account_id' => 2,
            'name' => 'Starting to Rust 2',
            'host' => '64.38.249.114',
            'password' => 'blorp',
            'port' => '28016',
            'timezone' => 'America/New_York',
            'max_players' => 50,
            'disabled' => 1,
            'order' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('servers')->insert([
            'account_id' => 2,
            'name' => 'Starting to Rust 3',
            'host' => '198.24.176.186',
            'password' => 'blorp',
            'port' => '28017',
            'timezone' => 'America/New_York',
            'max_players' => 100,
            'disabled' => 1,
            'order' => 2,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
    }
}
