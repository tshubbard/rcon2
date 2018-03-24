<?php

use Illuminate\Database\Seeder;

class AccountUserTableSeeder extends Seeder
{
    /**‰
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('account_user')->insert([
            'user_id' => 1,
            'account_id' => 1,
        ]);
        DB::table('account_user')->insert([
            'user_id' => 2,
            'account_id' => 1,
        ]);
        DB::table('account_user')->insert([
            'user_id' => 3,
            'account_id' => 1,
        ]);
        DB::table('account_user')->insert([
            'user_id' => 4,
            'account_id' => 2,
        ]);
        DB::table('account_user')->insert([
            'user_id' => 5,
            'account_id' => 2,
        ]);
        DB::table('account_user')->insert([
            'user_id' => 6,
            'account_id' => 2,
        ]);
    }
}
