<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'id' => 1,
            'name' => 'Default',
        ]);
        DB::table('roles')->insert([
            'id' => 2,
            'name' => 'Site User',
        ]);
        DB::table('roles')->insert([
            'id' => 3,
            'name' => 'Site Moderator',
        ]);
        DB::table('roles')->insert([
            'id' => 4,
            'name' => 'Site Admin',
        ]);
    }
}
