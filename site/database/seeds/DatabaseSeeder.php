<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ConfigTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(ItemTypesTableSeeder::class);
        $this->call(ItemsTableSeeder::class);
        $this->call(AccountsTableSeeder::class);
        $this->call(AccountUserTableSeeder::class);
        $this->call(AccountAclsTableSeeder::class);
        $this->call(AccountRolesTableSeeder::class);
        $this->call(ServersTableSeeder::class);
        $this->call(ServerEventsTableSeeder::class);
        $this->call(PlayersTableSeeder::class);
        $this->call(PlayersCurrentTableSeeder::class);
        $this->call(KillsTableSeeder::class);
        $this->call(TagsTableSeeder::class);
        $this->call(ServerEventTagTableSeeder::class);
    }
}
