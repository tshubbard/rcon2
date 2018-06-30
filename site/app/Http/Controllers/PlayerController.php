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
     * @param $serverId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($serverId)
    {
        // get the server by ID and return it
        $server = Server::find($serverId);

        $user = Auth::user();
        $accounts = $user->accounts;
        $account_valid = false;

        foreach($accounts as $row)
            if($row['id'] == $server['account_id'])
                $account_valid = true;

        if(!$account_valid)
            return response()->json([
                'success' => false,
                'data' => array(
                    'errors' => 'Invalid account permissions.'
                )
            ]);

        return response()->json(['success' => true, 'players' => $server->players]);
    }

    /**
     * Gets the current players for a specific server ID
     *
     * @param $serverId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function current($serverId)
    {
        // get the server by ID and return it
        $server = Server::find($serverId);

        $user = Auth::user();
        $accounts = $user->accounts;
        $account_valid = false;

        foreach($accounts as $row)
            if($row['id'] == $server['account_id'])
                $account_valid = true;

        if(!$account_valid)
            return response()->json([
                'success' => false,
                'data' => array(
                    'errors' => 'Invalid account permissions.'
                )
            ]);

        $players = DB::table('players_current')
            ->join('players', 'players.steam_id', '=', 'players_current.steam_id')
            ->select('players.*')
            ->get();

        return response()->json(['success' => true, 'players' => $players]);
    }

    /**
     * Kick the specified player
     *
     * @param $playerId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function kick($playerId)
    {
        $player = Player::find($playerId);
        $server = Server::find($player->server_id);

        $user = Auth::user();
        $accounts = $user->accounts;
        $account_valid = false;

        foreach($accounts as $row)
            if($row['id'] == $server['account_id'])
                $account_valid = true;

        if(!$account_valid)
            return response()->json([
                'success' => false,
                'data' => array(
                    'errors' => 'Invalid account permissions.'
                )
            ]);

        $c = curl_init('http://localhost:7869/api/server');
        curl_setopt_array($c, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => ('server_id=' . $player->server_id . '&operation=player.kick&steam_id=' . $player->steam_id)
        ));
        curl_exec($c);

        PlayerCurrent::where('steam_id', $player->steam_id)->first()->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Ban the specified player
     *
     * @param $playerId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function ban($playerId)
    {
        $player = Player::find($playerId);
        $server = Server::find($player->server_id);

        $user = Auth::user();
        $accounts = $user->accounts;
        $account_valid = false;

        foreach($accounts as $row)
            if($row['id'] == $server['account_id'])
                $account_valid = true;

        if(!$account_valid)
            return response()->json([
                'success' => false,
                'data' => array(
                    'errors' => 'Invalid account permissions.'
                )
            ]);

        $c = curl_init('http://localhost:7869/api/server');
        curl_setopt_array($c, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => ('server_id=' . $player->server_id . '&operation=player.ban&steam_id=' . $player->steam_id)
        ));
        curl_exec($c);

        PlayerCurrent::where('steam_id', $player->steam_id)->first()->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }

}
