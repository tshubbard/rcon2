<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Server;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$serverData = $this->_getDefaultViewData();

        //if (count($serverData['servers']) === 1) {
            // if there's only one server, just go to the server page
            //return redirect('/dashboard/' . $serverData['servers'][0]['id']);
        //}

        //$serverData = json_encode($serverData);

        //dd($serverData);
        return view('user.dashboard-layout', compact('serverData'));
    }

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
        $serverData = $this->_getDefaultViewData();

        // get the server by ID and return it
        $server = Server::find($serverId);
        $serverData['selectedServer'] = $server;

        $serverData = json_encode($serverData);
        return view('user.dashboard-layout', compact('serverData'));
    }

    /**
     * Returns any data we want available on logged-in views
     *
     * @return array
     */
    private function _getDefaultViewData()
    {
        $user = Auth::user();
        $viewData = array(
            'user' => $user,
            'servers' => $user->servers,
            'breadcrumbs' => array(
                array(
                    'label' => 'Dashboard',
                    'url' => '/dashboard/'
                ),
            ),
        );

        return $viewData;
    }

	public function servers()
	{
		$servers = Auth::user()->servers->toArray();

		foreach($servers as $key => $value)
			$servers[$key]['events'] = \App\ServerEvent::find(array('server_id' => $value['id']))->toArray();

		return $servers;
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
