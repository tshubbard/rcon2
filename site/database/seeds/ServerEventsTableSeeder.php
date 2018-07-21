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
            'created_by_user_id' => 1,
            'description' => 'This is my usual server message for my Non-KoS server that runs every 15 minutes to let people know the rules.',
            'event_type' => 'timer',
            'commands' => '[{"order":1,"key":"say","command":"say Please note this is a non-KOS server blah blah."}]',
            'command_timer' => 15,
            'is_indefinite' => 1,
            'is_public' => 1,
            'is_active' => true,
            'votes' => 20,
            'copy_count' => 4,
            'start_date' => Carbon::now()->format('Y-m-d H:i:s'),
            'end_date' => Carbon::create(2020, 1, 14)->format('Y-m-d H:i:s'),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('server_events')->insert([
            'name' => 'PURGE KoS Server Message',
            'server_id' => 1,
            'created_by_user_id' => 1,
            'description' => 'We host a "Purge" event the last 3 days of a wipe where everyone can KoS. This is the message that displays every 15 minutes.',
            'event_type' => 'timer',
            'commands' => '[{"order":1,"key":"say","command":"say PURGE IS ACTIVE -- No PVP Rules."}]',
            'command_timer' => 60,
            'is_indefinite' => 1,
            'is_public' => 1,
            'is_active' => false,
            'votes' => 4,
            'copy_count' => 2,
            'start_date' => Carbon::now()->format('Y-m-d H:i:s'),
            'end_date' => Carbon::create(2020, 1, 14)->format('Y-m-d H:i:s'),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('server_events')->insert([
            'name' => 'Kit 1',
            'server_id' => 1,
            'created_by_user_id' => 1,
            'description' => 'During the Purge, users can ask to get an AK',
            'event_type' => 'player.chat',
            'commands' => '[{"order":1,"key":"giveto","command":"giveto ${user.steam_id} rifle.ak 1"},{"order":2,"key":"say","command":"say Server armed ${user.username} with an AK!"}]',
            'command_trigger' => '!kit1',
            'is_indefinite' => 1,
            'is_public' => 1,
            'is_active' => true,
            'votes' => 15,
            'copy_count' => 10,
            'start_date' => Carbon::now()->format('Y-m-d H:i:s'),
            'end_date' => Carbon::create(2020, 1, 14)->format('Y-m-d H:i:s'),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('server_events')->insert([
            'name' => 'No KoS Server Message',
            'server_id' => 4,
            'created_by_user_id' => 1,
            'description' => null,
            'event_type' => 'timer',
            'commands' => '[{"order":1,"key":"say","command":"say Please note this is a non-KOS server blah blah."}]',
            'command_timer' => 15,
            'is_indefinite' => 1,
            'is_public' => 1,
            'is_active' => true,
            'votes' => 200,
            'copy_count' => 1,
            'start_date' => Carbon::now()->format('Y-m-d H:i:s'),
            'end_date' => Carbon::create(2020, 1, 14)->format('Y-m-d H:i:s'),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('server_events')->insert([
            'name' => 'PURGE KoS Server Message',
            'server_id' => 4,
            'created_by_user_id' => 1,
            'event_type' => 'timer',
            'description' => null,
            'commands' => '[{"order":1,"key":"say","command":"say PURGE IS ACTIVE -- No PVP Rules."}]',
            'command_timer' => 60,
            'is_indefinite' => 1,
            'is_public' => 1,
            'is_active' => false,
            'votes' => 20,
            'copy_count' => 2,
            'start_date' => Carbon::now()->format('Y-m-d H:i:s'),
            'end_date' => Carbon::create(2020, 1, 14)->format('Y-m-d H:i:s'),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('server_events')->insert([
            'name' => 'Kit 1',
            'server_id' => 4,
            'created_by_user_id' => 1,
            'description' => null,
            'event_type' => 'player.chat',
            'commands' => '[{"order":1,"key":"giveto","command":"giveto ${user.steam_id} rifle.ak 1"},{"order":2,"key":"say","command":"say Server armed ${user.username} with an AK!"}]',
            'command_trigger' => '!kit1',
            'is_indefinite' => 1,
            'is_public' => 1,
            'is_active' => true,
            'votes' => 18,
            'copy_count' => 3,
            'start_date' => Carbon::now()->format('Y-m-d H:i:s'),
            'end_date' => Carbon::create(2020, 1, 14)->format('Y-m-d H:i:s'),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
    }
}

