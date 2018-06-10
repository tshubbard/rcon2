<?php

namespace App\Http\Controllers;

use App\Player;
use App\Server;
use Validator;
use Illuminate\Http\Request;
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }

}
