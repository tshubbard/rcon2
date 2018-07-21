<?php

use Illuminate\Database\Seeder;

class ServerEventTagTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('server_event_tag')->insert([
            'server_event_id' => 1,
            'tag_id' => 1,
        ]);
        DB::table('server_event_tag')->insert([
            'server_event_id' => 2,
            'tag_id' => 1,
        ]);
        DB::table('server_event_tag')->insert([
            'server_event_id' => 3,
            'tag_id' => 2,
        ]);
        DB::table('server_event_tag')->insert([
            'server_event_id' => 3,
            'tag_id' => 1,
        ]);
        DB::table('server_event_tag')->insert([
            'server_event_id' => 4,
            'tag_id' => 1,
        ]);
        DB::table('server_event_tag')->insert([
            'server_event_id' => 5,
            'tag_id' => 1,
        ]);
        DB::table('server_event_tag')->insert([
            'server_event_id' => 6,
            'tag_id' => 2,
        ]);
        DB::table('server_event_tag')->insert([
            'server_event_id' => 6,
            'tag_id' => 1,
        ]);
    }
}
