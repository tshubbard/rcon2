<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ChatTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('chat')->insert([
            'server_id' => 1,
            'steam_id' => '76561198799966583',
            'username' => 'tadisxshaw16',
            'message' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt.'
        ]);

        DB::table('chat')->insert([
            'server_id' => 1,
            'steam_id' => '76561198319784352',
            'username' => 't-roids',
            'message' => 'Ut enim ad minima veniam, quis nostrum exercitationem.'
        ]);

        DB::table('chat')->insert([
            'server_id' => 1,
            'steam_id' => '76561198426354098',
            'username' => 'RolePlayer',
            'message' => 'Quis autem vel eum iure reprehenderit qui in ea voluptate velit.'
        ]);

        DB::table('chat')->insert([
            'server_id' => 1,
            'steam_id' => '76561198208959985',
            'username' => 'Ryuk',
            'message' => 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium.'
        ]);
    }
}
