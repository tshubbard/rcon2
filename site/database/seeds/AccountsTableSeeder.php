<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class AccountsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('accounts')->insert([
            'id' => 1,
            'name' => 'Badass Admins',
            'slug' => 'badass-admins',
            'owner_id' => 1,
            'user_count' => 3,
            'description' => 'Just a couple badass admins doing some badass shit',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('accounts')->insert([
            'id' => 2,
            'name' => 'Dummy Users',
            'slug' => 'dummy-users',
            'owner_id' => 1,
            'user_count' => 3,
            'description' => 'Just a couple dummy users doing some dummy user shit',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
    }
}
