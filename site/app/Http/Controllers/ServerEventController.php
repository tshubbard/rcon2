<?php

namespace App\Http\Controllers;

use App\ServerEvent;
use Illuminate\Http\Request;
use Validator;

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
        $validator = Validator::make($requestInput, array(
            'server_id' => 'numeric',
            'interval' => 'numeric',
            'is_indefinite' => 'numeric',
            'is_public' => 'numeric',
            'is_active' => 'numeric',
            'votes' => 'numeric',
        ));

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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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
