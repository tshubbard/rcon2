<?php

namespace App\Http\Controllers;

use App\ServerEvent;
use App\Server;
use Illuminate\Http\Request;
use Validator;
use Auth;

class ServerEventController extends Controller
{

    /**
     * Display a listing of server events by server ID
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ServerEvent  $serverEvent
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, ServerEvent $serverEvent)
    {
        return response()->json($serverEvent);
    }

    public function eventsList()
    {
        // GET /games
        $events = ServerEvent::public()->byVotes()->get();

        return response()->json($events);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ServerEvent  $serverEvent
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, ServerEvent $serverEventId)
    {
        //
    }

    protected function getValidationForEvent($eventData)
    {
        $vRules = array(
            'server_id' => 'numeric',
            'event_type' => 'string',
        );

        if ($eventData['event_type'] === 'timer') {
            $vRules['command_timer'] = 'numeric';
        }
        if ($eventData['event_type'] === 'player.chat') {
            $vRules['command_trigger'] = 'string';
        }

        return $vRules;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $requestInput = $request->all();
        $validationRules = $this->getValidationForEvent($requestInput);
        $validator = Validator::make($requestInput, $validationRules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => array(
                    'errors' => $validator->failed()
                )
            ]);
        }

        $server = Server::find($requestInput['server_id']);
        if(!$this->checkAccount($server->account_id)) return $this->checkAccountFail();

        $requestInput['created_by_user_id'] = Auth::user()->id;
        $newServerEvent = ServerEvent::create($requestInput);

        $c = curl_init('http://localhost:7869/api/server');
        curl_setopt_array($c, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => ('server_id=' . $newServerEvent->server_id . '&operation=event.update&event_id=' . $newServerEvent->id . '&event_type=' . $newServerEvent->event_type)
        ));
        curl_exec($c);

        return response()->json([
            'success' => true,
            'data' => $newServerEvent
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ServerEvent  $serverEvent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ServerEvent $serverEvent)
    {
        $requestInput = $request->all();
        $validationRules = $this->getValidationForEvent($requestInput);
        $validator = Validator::make($requestInput, $validationRules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => array(
                    'errors' => $validator->failed()
                )
            ]);
        }

        $current_type = $serverEvent->event_type;

        $server = Server::find($serverEvent->server_id);
        if(!$this->checkAccount($server->account_id)) return $this->checkAccountFail();

        $serverEvent->update($requestInput);

        $c = curl_init('http://localhost:7869/api/server');
        curl_setopt_array($c, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => ('server_id=' . $serverEvent->server_id . '&operation=event.update&event_id=' . $serverEvent->id . '&event_type=' . $current_type)
        ));
        curl_exec($c);

        return response()->json($serverEvent);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ServerEvent  $serverEvent
     * @return \Illuminate\Http\Response
     */
    public function updateActive(Request $request, ServerEvent $serverEvent, $isActive)
    {
        $requestInput = [
            'is_active' => $isActive
        ];
        $validationRules = [
            'is_active' => 'in:0,1',
        ];
        $validator = Validator::make($requestInput, $validationRules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => array(
                    'errors' => $validator->failed()
                )
            ]);
        }

        $server = Server::find($serverEvent->server_id);
        if(!$this->checkAccount($server->account_id)) return $this->checkAccountFail();

        $serverEvent->update($requestInput);
        $serverEvent->is_active = intval($isActive);

        $c = curl_init('http://localhost:7869/api/server');
        curl_setopt_array($c, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => ('server_id=' . $serverEvent->server_id . '&operation=event.update&event_id=' . $serverEvent->id . '&event_type=' . $serverEvent->event_type)
        ));
        $res = curl_exec($c);

        return response()->json($serverEvent);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ServerEvent  $serverEvent
     * @return \Illuminate\Http\Response
     */
    public function destroy(ServerEvent $serverEvent)
    {
        $server = Server::find($serverEvent->server_id);
        if(!$this->checkAccount($server->account_id)) return $this->checkAccountFail();

        ServerEvent::destroy($serverEvent->id);

        $c = curl_init('http://localhost:7869/api/server');
        curl_setopt_array($c, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => ('server_id=' . $serverEvent->server_id . '&operation=event.update&event_id=' . $serverEvent->id . '&event_type=' . $serverEvent->event_type)
        ));
        curl_exec($c);

        return response()->json($serverEvent);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ServerEvent  $serverEvent
     * @return \Illuminate\Http\Response
     */
    public function edit(ServerEvent $serverEvent)
    {
        //
    }
}
