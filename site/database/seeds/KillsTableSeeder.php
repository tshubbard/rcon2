<?php

use Illuminate\Database\Seeder;

class KillsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = array(
            array(
                "server_id" => 1,
                "created_at" =>"2018-01-24 23:32:36",
                "victim_steam_id" => "76561197993023958",
                "victim_username" => "Haelix",
                "killer_steam_id" => "76561197992834824",
                "killer_username" => "LordAmish",
            ),
            array(
                "server_id" => 1,
                "created_at" =>"2018-01-25 03:48:46",
                "victim_steam_id" => "76561198420774871",
                "victim_username" => "{RP} Dreamer",
                "killer_steam_id" => NULL,
                "killer_username" => "landmine (entity)",
            ),
            array(
                "server_id" => 1,
                "created_at" =>"2018-01-25 03:50:26",
                "victim_steam_id" => "76561198099001888",
                "victim_username" => "[₪] Imperium",
                "killer_steam_id" => NULL,
                "killer_username" => "Cold",
            ),
            array(
                "server_id" => 1,
                "created_at" =>"2018-01-25 03:52:26",
                "victim_steam_id" => "76561198282368175",
                "victim_username" => "Bondy",
                "killer_steam_id" => NULL,
                "killer_username" => "bear (Bear)",
            ),
            array(
                "server_id" => 1,
                "created_at" =>"2018-01-25 03:54:26",
                "victim_steam_id" => "76561198106681757",
                "victim_username" => "I FUCKING LOVE ROSE TICO",
                "killer_steam_id" => NULL,
                "killer_username" => "spikes.floor (entity)",
            ),
            array(
                "server_id" => 1,
                "created_at" =>"2018-01-25 03:56:26",
                "victim_steam_id" => "76561198076362687",
                "victim_username" => "HesitantNova",
                "killer_steam_id" => NULL,
                "killer_username" => "Radiation",
            ),
            array(
                "server_id" => 1,
                "created_at" =>"2018-01-25 03:58:26",
                "victim_steam_id" => "76561198429672498",
                "victim_username" => "Yesyes good",
                "killer_steam_id" => NULL,
                "killer_username" => "Hunger",
            ),
            array(
                "server_id" => 1,
                "created_at" =>"2018-01-25 04:00:26",
                "victim_steam_id" => "76561198283527933",
                "victim_username" => "DragginDeez",
                "killer_steam_id" => "76561198429672498",
                "killer_username" => "Yesyes good",
            ),
            array(
                "server_id" => 1,
                "created_at" =>"2018-01-25 04:10:26",
                "victim_steam_id" => "76561198212118039",
                "victim_username" => "Island",
                "killer_steam_id" => "76561198208959985",
                "killer_username" => "Ryuk",
            ),
            array(
                "server_id" => 1,
                "created_at" =>"2018-01-25 04:20:26",
                "victim_steam_id" => "76561198319784352",
                "victim_username" => "t-roids",
                "killer_steam_id" => "76561198426354098",
                "killer_username" => "RolePlayer",
            ),
        );
        foreach ($items as $item) {
            DB::table('kills')->insert([
                'server_id' => $item['server_id'],
                'created_at' => $item['created_at'],
                'victim_steam_id' => $item['victim_steam_id'],
                'victim_username' => $item['victim_username'],
                'killer_steam_id' => $item['killer_steam_id'],
                'killer_username' => $item['killer_username'],
            ]);
        }
    }
}
