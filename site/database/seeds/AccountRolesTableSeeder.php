<?php

use Illuminate\Database\Seeder;

class AccountRolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('account_roles')->insert([
            'id' => 1,
            'name' => 'Junior Mod',
            'description' => 'The Junior Mod role can only view your account, servers on the account, and events and players on those servers.',
            'acls' => '"{"account":{"billing":false,"delete":false,"edit":false,"view":true},"events":{"create":false,"delete":false,"edit":false,"toggleOnOff":false,"view":true},"players":{"ban":false,"delete":false,"kick":false,"view":true},"servers":{"create":false,"delete":false,"edit":false,"view":true,"viewServerPassword":false}}"',
        ]);
        DB::table('account_roles')->insert([
            'id' => 2,
            'name' => 'Moderator',
            'description' => 'The Mod role has limited access to your server, players, and events, but not your account.',
            'acls' => '"{"account":{"billing":false,"delete":false,"edit":false,"view":true},"events":{"create":false,"delete":false,"edit":false,"toggleOnOff":true,"view":true},"players":{"ban":false,"delete":false,"kick":true,"view":true},"servers":{"create":false,"delete":false,"edit":true,"view":true,"viewServerPassword":false}}"',
        ]);
    }
}
