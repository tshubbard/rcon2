<?php

use Illuminate\Database\Seeder;

class PlayersCurrentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = array(
            array("server_id" => 1, "steam_id" =>"76561198799966583"),
            array("server_id" => 1, "steam_id" =>"76561198319784352"),
            array("server_id" => 1, "steam_id" =>"76561198426354098"),
            array("server_id" => 1, "steam_id" =>"76561197993023958"),
            array("server_id" => 1, "steam_id" =>"76561197992834824"),
            array("server_id" => 1, "steam_id" =>"76561198208959985"),
            array("server_id" => 1, "steam_id" =>"76561198212118039"),
            array("server_id" => 1, "steam_id" =>"76561198052180015"),
            array("server_id" => 1, "steam_id" =>"76561198106681757"),
            array("server_id" => 1, "steam_id" =>"76561198420774871"),
            array("server_id" => 1, "steam_id" =>"76561198099001888"),
            array("server_id" => 1, "steam_id" =>"76561198799966583"),
            array("server_id" => 1, "steam_id" =>"76561198282368175"),
            array("server_id" => 1, "steam_id" =>"76561198076362687"),
            array("server_id" => 1, "steam_id" =>"76561198429672498"),
            array("server_id" => 1, "steam_id" =>"76561198283527933"),
        );
        foreach ($items as $item) {
            DB::table('players_current')->insert([
                'server_id' => $item['server_id'],
                'steam_id' => $item['steam_id'],
            ]);
        }
    }
}
