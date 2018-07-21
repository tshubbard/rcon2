<?php

use Illuminate\Database\Seeder;

class AccountAclsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('account_acls')->insert([
            'id' => 1,
            'name' => 'Account',
            'slug' => 'account',
            'description' => 'ACLs for the Account level',
            'parent_slug' => null,
        ]);
        DB::table('account_acls')->insert([
            'id' => 2,
            'name' => 'Billing',
            'slug' => 'billing',
            'description' => 'Does the User have access to set-up or edit Billing on the Account?',
            'parent_slug' => 'account',
        ]);
        DB::table('account_acls')->insert([
            'id' => 3,
            'name' => 'Delete',
            'slug' => 'delete',
            'description' => 'Can the User delete the Account?',
            'parent_slug' => 'account',
        ]);
        DB::table('account_acls')->insert([
            'id' => 4,
            'name' => 'Edit',
            'slug' => 'edit',
            'description' => 'Can the User edit Account settings except Billing?',
            'parent_slug' => 'account',
        ]);
        DB::table('account_acls')->insert([
            'id' => 5,
            'name' => 'View',
            'slug' => 'view',
            'description' => 'Can the User view Account settings?',
            'parent_slug' => 'account',
        ]);
        DB::table('account_acls')->insert([
            'id' => 6,
            'name' => 'Events',
            'slug' => 'events',
            'description' => 'ACLs for Events on Account Servers',
            'parent_slug' => null,
        ]);
        DB::table('account_acls')->insert([
            'id' => 7,
            'name' => 'Create',
            'slug' => 'create',
            'description' => 'Can the User create Events on the Server?',
            'parent_slug' => 'events',
        ]);
        DB::table('account_acls')->insert([
            'id' => 8,
            'name' => 'Delete',
            'slug' => 'delete',
            'description' => 'Can the User delete Events on the Server?',
            'parent_slug' => 'events',
        ]);
        DB::table('account_acls')->insert([
            'id' => 9,
            'name' => 'Edit',
            'slug' => 'edit',
            'description' => 'Can the User edit Events on the Server?',
            'parent_slug' => 'events',
        ]);
        DB::table('account_acls')->insert([
            'id' => 10,
            'name' => 'Toggle On/Off',
            'slug' => 'toggleOnOff',
            'description' => 'Can the User toggle Server Events on and off?',
            'parent_slug' => 'events',
        ]);
        DB::table('account_acls')->insert([
            'id' => 11,
            'name' => 'View',
            'slug' => 'view',
            'description' => 'Can the User view Server Events?',
            'parent_slug' => 'events',
        ]);
        DB::table('account_acls')->insert([
            'id' => 12,
            'name' => 'Players',
            'slug' => 'players',
            'description' => 'ACLs for Player Data and Actions on the Account',
            'parent_slug' => null,
        ]);
        DB::table('account_acls')->insert([
            'id' => 13,
            'name' => 'Ban',
            'slug' => 'ban',
            'description' => 'Can the User ban Players from your Server?',
            'parent_slug' => 'players',
        ]);
        DB::table('account_acls')->insert([
            'id' => 14,
            'name' => 'Delete',
            'slug' => 'delete',
            'description' => 'Can the User delete Player data from your Server?',
            'parent_slug' => 'players',
        ]);
        DB::table('account_acls')->insert([
            'id' => 15,
            'name' => 'Kick',
            'slug' => 'kick',
            'description' => 'Can the User kick Players from your Server?',
            'parent_slug' => 'players',
        ]);
        DB::table('account_acls')->insert([
            'id' => 16,
            'name' => 'View',
            'slug' => 'view',
            'description' => 'Can the User view Players on your Server?',
            'parent_slug' => 'players',
        ]);
        DB::table('account_acls')->insert([
            'id' => 17,
            'name' => 'Servers',
            'slug' => 'servers',
            'description' => 'ACLs for Servers on the Account',
            'parent_slug' => null,
        ]);
        DB::table('account_acls')->insert([
            'id' => 18,
            'name' => 'Create',
            'slug' => 'create',
            'description' => 'Can the User create new Servers on the Account?',
            'parent_slug' => 'servers',
        ]);
        DB::table('account_acls')->insert([
            'id' => 19,
            'name' => 'Delete',
            'slug' => 'delete',
            'description' => 'Can the User delete Servers from the Account?',
            'parent_slug' => 'servers',
        ]);
        DB::table('account_acls')->insert([
            'id' => 20,
            'name' => 'Edit',
            'slug' => 'edit',
            'description' => 'Can the User edit Servers on the Account?',
            'parent_slug' => 'servers',
        ]);
        DB::table('account_acls')->insert([
            'id' => 21,
            'name' => 'View',
            'slug' => 'view',
            'description' => 'Can the User view Servers on the Account?',
            'parent_slug' => 'servers',
        ]);
        DB::table('account_acls')->insert([
            'id' => 22,
            'name' => 'Edit Server Password',
            'slug' => 'editServerPW',
            'description' => 'Can the User view and edit the Server Password?',
            'parent_slug' => 'servers',
        ]);
    }
}
