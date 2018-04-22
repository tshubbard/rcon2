<?php

namespace App\Http\Controllers;

use App\Server;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

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

        $requestInput['user_id'] = \Auth::user()->id();
        $newServer = Server::create($requestInput);

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

        $newServer = Server::find($requestInput['id']);
        $newServer->update($requestInput);

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
        Server::destroy($id);
    }

}
