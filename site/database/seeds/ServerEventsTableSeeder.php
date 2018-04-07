<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ServerEventsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('server_events')->insert([
            'name' => 'No KoS Server Message',
            'server_id' => 1,
            'event_type' => 'timer',
            'commands' => '[{"key":"say","command":"say Please note this is a non-KOS server blah blah.","command_text":"Please note this is a non-KOS server blah blah."}]',
            'command_timer' => 15,
            'is_indefinite' => 1,
            'is_public' => 1,
            'is_active' => true,
            'votes' => 20,
            'start_date' => Carbon::now()->format('Y-m-d H:i:s'),
            'end_date' => Carbon::create(2020, 1, 14)->format('Y-m-d H:i:s'),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('server_events')->insert([
            'name' => 'PURGE KoS Server Message',
            'server_id' => 1,
            'event_type' => 'timer',
            'commands' => '[{"key":"say","command":"say PURGE IS ACTIVE -- No PVP Rules.","command_text":"PURGE IS ACTIVE -- No PVP Rules."}]',
            'command_timer' => 60,
            'is_indefinite' => 1,
            'is_public' => 1,
            'is_active' => false,
            'votes' => 4,
            'start_date' => Carbon::now()->format('Y-m-d H:i:s'),
            'end_date' => Carbon::create(2020, 1, 14)->format('Y-m-d H:i:s'),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('server_events')->insert([
            'name' => 'Kit 1',
            'server_id' => 1,
            'event_type' => 'player.chat',
            'commands' => '[{"key":"giveto","command":"giveto ${user.steam_id} rifle.ak 1","command_itemid":"rifle.ak","command_target":"${user.steam_id}","command_quantity":1},{"key":"say","command":"say Server armed ${user.username} with an AK!","command_text":"Server armed ${user.username} with an AK!"}]',
            'command_trigger' => '!kit1',
            'is_indefinite' => 1,
            'is_public' => 1,
            'is_active' => true,
            'votes' => 15,
            'start_date' => Carbon::now()->format('Y-m-d H:i:s'),
            'end_date' => Carbon::create(2020, 1, 14)->format('Y-m-d H:i:s'),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('server_events')->insert([
            'name' => 'No KoS Server Message',
            'server_id' => 4,
            'event_type' => 'timer',
            'commands' => '[{"key":"say","command":"say Please note this is a non-KOS server blah blah.","command_text":"Please note this is a non-KOS server blah blah."}]',
            'command_timer' => 15,
            'is_indefinite' => 1,
            'is_public' => 1,
            'is_active' => true,
            'votes' => 200,
            'start_date' => Carbon::now()->format('Y-m-d H:i:s'),
            'end_date' => Carbon::create(2020, 1, 14)->format('Y-m-d H:i:s'),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('server_events')->insert([
            'name' => 'PURGE KoS Server Message',
            'server_id' => 4,
            'event_type' => 'timer',
            'commands' => '[{"key":"say","command":"say PURGE IS ACTIVE -- No PVP Rules.","command_text":"PURGE IS ACTIVE -- No PVP Rules."}]',
            'command_timer' => 60,
            'is_indefinite' => 1,
            'is_public' => 1,
            'is_active' => false,
            'votes' => 20,
            'start_date' => Carbon::now()->format('Y-m-d H:i:s'),
            'end_date' => Carbon::create(2020, 1, 14)->format('Y-m-d H:i:s'),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('server_events')->insert([
            'name' => 'Kit 1',
            'server_id' => 4,
            'event_type' => 'player.chat',
            'commands' => '[{"key":"giveto","command":"giveto ${user.steam_id} rifle.ak 1","command_itemid":"rifle.ak","command_target":"${user.steam_id}","command_quantity":1},{"key":"say","command":"say Server armed ${user.username} with an AK!","command_text":"Server armed ${user.username} with an AK!"}]',
            'command_trigger' => '!kit1',
            'is_indefinite' => 1,
            'is_public' => 1,
            'is_active' => true,
            'votes' => 18,
            'start_date' => Carbon::now()->format('Y-m-d H:i:s'),
            'end_date' => Carbon::create(2020, 1, 14)->format('Y-m-d H:i:s'),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
    }
}

