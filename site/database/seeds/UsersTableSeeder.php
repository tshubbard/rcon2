<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Haelix',
            'slug' => 'haelix',
            'email' => 'tshubbard2@gmail.com',
            'password' => bcrypt('secret'),
            'verified' => 1,
            'verification_token' => 'test',
            'role_id' => 4,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('users')->insert([
            'name' => 'LordAmish',
            'slug' => 'lordamish',
            'email' => 'adam.voigt@gmail.com',
            'password' => bcrypt('secret'),
            'verified' => 1,
            'verification_token' => 'test',
            'role_id' => 4,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('users')->insert([
            'name' => 'Turd Fergusen (Test Default Role)',
            'slug' => 'turd-fergusen-test-default-role',
            'email' => 'role1@gmail.com',
            'password' => bcrypt('secret'),
            'verified' => 1,
            'verification_token' => 'test',
            'role_id' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('users')->insert([
            'name' => 'Turd Fergusen (Test User Role)',
            'slug' => 'turd-fergusen-test-user-role',
            'email' => 'role2@gmail.com',
            'password' => bcrypt('secret'),
            'verified' => 1,
            'verification_token' => 'test',
            'role_id' => 2,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('users')->insert([
            'name' => 'Turd Fergusen (Test Mod Role)',
            'slug' => 'turd-fergusen-test-mod-role',
            'email' => 'role3@gmail.com',
            'password' => bcrypt('secret'),
            'verified' => 1,
            'verification_token' => 'test',
            'role_id' => 3,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
    }
}
