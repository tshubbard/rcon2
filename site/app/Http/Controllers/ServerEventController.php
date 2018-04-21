<?php

namespace App\Http\Controllers;

use App\ServerEvent;
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

        $requestInput['created_by_user_id'] = Auth::user()->id;
        $newServerEvent = ServerEvent::create($requestInput);

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

        $serverEvent->update($requestInput);

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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ServerEvent  $serverEvent
     * @return \Illuminate\Http\Response
     */
    public function destroy(ServerEvent $serverEvent)
    {
        //
    }
}
