<?php

namespace App\Http\Controllers;

use App\Chat;
use App\Server;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Auth;

class ChatController extends Controller
{
    /**
     * Gets the chat for a specific server ID
     *
     * @param Server $server
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Server $server, $page = 1)
    {
        if(!$this->checkAccount($server->account_id)) return $this->checkAccountFail();

        $chat = Chat::where('server_id', '=', $server->id)->orderBy('id', 'desc')->offset(100 * ($page-1))->limit(100)->get();

        return response()->json([
            'messages' => $chat
        ]);
    }
}
