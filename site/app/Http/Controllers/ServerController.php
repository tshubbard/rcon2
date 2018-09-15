<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Server;
use App\ServerEvent;

class ServerController extends Controller
{
    protected $validationRules = array(
        'name' => 'required',
        'host' => 'required',
        //'password' => 'required',
        'port' => 'required|numeric',
        //'max_players' => 'required|numeric',
    );

    /**
     * Gets the data for a specific server ID
     *
     * GET /dashboard/{serverId}
     *
     * @param $serverId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($serverId)
    {
        // get the server by ID and return it
        $server = Server::find($serverId);

        if(!$this->checkAccount($server->account_id)) return $this->checkAccountFail();

        $resp = [
            'server' => $server,
            'events' => []
        ];
        $events = $server->serverEvents;
        if (count($events)) {
            $resp['events'] = $events;
        }
        return response()->json($resp);
    }


    /**
     * API - Display the specified Server in JSON
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|numeric  $serverSlug The server slug or id to return
     * @return \Illuminate\Http\Response
     */
    public function showJSON(Request $request, $serverSlug)
    {
        if (is_numeric($serverSlug)) {
            $field = 'id';
        } else {
            $field = 'slug';
        }
        $server = Server::where($field , '=', $serverSlug)->first();

        if(!$this->checkAccount($server->account_id)) return $this->checkAccountFail();

        // weird way to populate $server users
        $server->users;
        $server->servers;

        return response()->json($server);
    }

    /**
     * API - Send the servers on a user's account in JSON
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function serversIndexJSON(Request $request)
    {
        // get all the accounts a user is assigned to
        $accounts = Auth::user()->accounts;
        $servers = [];

        // loop over accounts and get servers for each account
        foreach($accounts as $account) {
            $servers = $account->servers;
            foreach ($servers as &$server) {
                $serverData = $server->toArray();
                // get server events for each server
                $server['events'] = ServerEvent::where([
                    ['server_id', '=', $serverData['id']],
                    ['deleted_at', '=', null]
                ])->get();

                foreach($server['events'] as &$event) {
                    $event->tags;
                }
            }
        }

        return response()->json($servers);
    }

    /**
     * Store a newly created resource in storage.  POST /server
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $requestInput = $request->all();
        $validator = Validator::make($requestInput, $this->validationRules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => array(
                    'errors' => $validator->failed()
                )
            ]);
        }

        if(!$this->checkAccount($requestInput['account_id'])) return $this->checkAccountFail();

        $requestInput['timezone'] = 'America/New_York';

        if(empty($requestInput['is_active']))
            $requestInput['is_active'] = 0;

        $requestInput['slug'] = sha1($requestInput['name'] . $requestInput['account_id'] . microtime());

        $newServer = Server::create($requestInput);

        $c = curl_init('http://localhost:7869/api/server');
        curl_setopt_array($c, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => ('server_id=' . $newServer->id . '&operation=server.update')
        ));
        curl_exec($c);

        return response()->json([
            'success' => true,
            'data' => $newServer
        ]);
    }

    /**
     * Update the specified resource in storage. PUT
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $requestInput = $request->all();
        $validator = Validator::make($requestInput, $this->validationRules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => array(
                    'errors' => $validator->failed()
                )
            ]);
        }

        if(empty($requestInput['is_active']))
            $requestInput['is_active'] = 0;

        $newServer = Server::find($id);

        if(!$this->checkAccount($newServer->account_id)) return $this->checkAccountFail();

        $newServer->update($requestInput);

        $c = curl_init('http://localhost:7869/api/server');
        curl_setopt_array($c, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => ('server_id=' . $id . '&operation=server.update')
        ));
        curl_exec($c);

        return response()->json([
            'success' => true,
            'data' => $newServer
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $server = Server::find($id);

        if(!$this->checkAccount($server->account_id)) return $this->checkAccountFail();

        Server::destroy($id);

        $c = curl_init('http://localhost:7869/api/server');
        curl_setopt_array($c, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => ('server_id=' . $id . '&operation=server.update')
        ));
        curl_exec($c);

        return response()->json([
            'success' => true,
            'data' => array('id' => (int) $id, 'deleted' => true)
        ]);
    }

}
