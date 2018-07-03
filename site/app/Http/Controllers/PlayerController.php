<?php

namespace App\Http\Controllers;

use App\Player;
use App\PlayerCurrent;
use App\Server;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Auth;

class PlayerController extends Controller
{
    /**
     * Gets the players for a specific server ID
     *
     * @param Server $server
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Server $server)
    {
        $user = Auth::user();
        $accounts = $user->accounts;
        $account_valid = false;

        foreach ($accounts as $row) {
            if ($row['id'] == $server['account_id']) {
                $account_valid = true;
            }
        }

        if (!$account_valid) {
            return response()->json([
                'success' => false,
                'data' => array(
                    'errors' => 'Invalid account permissions.',
                ),
            ]);
        }

        return response()->json([
            'players' => $server->players
        ]);
    }

    /**
     * Gets the current players for a specific server ID
     *
     * @param Server $server
     *
     * @return \Illuminate\Http\Response
     */
    public function current(Server $server)
    {
        $user = Auth::user();
        $accounts = $user->accounts;
        $account_valid = false;

        foreach ($accounts as $row) {
            if ($row['id'] == $server['account_id']) {
                $account_valid = true;
            }
        }

        if (!$account_valid) {
            return response()->json(
                [
                    'success' => false,
                    'data' => array(
                        'errors' => 'Invalid account permissions.',
                    ),
                ]
            );
        }

        $players = DB::table('players_current')
            ->join('players', 'players.steam_id', '=', 'players_current.steam_id')
            ->select('players.*')
            ->get();

        return response()->json([
            'players' => $players
        ]);
    }

    /**
     * Kick the specified player
     *
     * @param Player $playerId
     *
     * @return \Illuminate\Http\Response
     */
    public function kick(Player $player)
    {
        $server = Server::find($player->server_id);

        $user = Auth::user();
        $accounts = $user->accounts;
        $account_valid = false;

        foreach ($accounts as $row) {
            if ($row['id'] == $server['account_id']) {
                $account_valid = true;
            }
        }

        if (!$account_valid) {
            return response()->json(
                [
                    'success' => false,
                    'data' => array(
                        'errors' => 'Invalid account permissions.',
                    ),
                ]
            );
        }

        $c = curl_init('http://localhost:7869/api/server');
        curl_setopt_array(
            $c,
            array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => ('server_id=' . $player->server_id . '&operation=player.kick&steam_id=' . $player->steam_id),
            )
        );
        curl_exec($c);

        PlayerCurrent::where('steam_id', $player->steam_id)->first()->delete();

        return response()->json($player);
    }

    /**
     * Ban the specified player
     *
     * @param Player $player
     *
     * @return \Illuminate\Http\Response
     */
    public function ban(Player $player)
    {
        $server = Server::find($player->server_id);

        $user = Auth::user();
        $accounts = $user->accounts;
        $account_valid = false;

        foreach ($accounts as $row) {
            if ($row['id'] == $server['account_id']) {
                $account_valid = true;
            }
        }

        if (!$account_valid) {
            return response()->json(
                [
                    'success' => false,
                    'data' => array(
                        'errors' => 'Invalid account permissions.',
                    ),
                ]
            );
        }

        $c = curl_init('http://localhost:7869/api/server');
        curl_setopt_array(
            $c,
            array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => ('server_id=' . $player->server_id . '&operation=player.ban&steam_id=' . $player->steam_id),
            )
        );
        curl_exec($c);

        PlayerCurrent::where('steam_id', $player->steam_id)->first()->delete();

        return response()->json($player);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Player $player
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Player $player)
    {
        $player->delete();
        $player->server()->dissociate();

        return response()->json($player);
    }

}
