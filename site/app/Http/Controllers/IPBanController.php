<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Auth;
use App\Server;
use App\IPBan;

class IPBanController extends Controller
{
    protected $validationRules = array(
        'name' => 'required',
        'ipaddress' => 'required'
    );

    public function ipbansIndexJSON(Server $server)
    {
        $ipbans = DB::table('server_ipbans')
            ->select('*')
            ->where('server_id', '=', $server->id)
            ->orderBy('id')
            ->get();

        return response()->json($ipbans);
    }

    public function store(Request $request)
    {
	    //if(!$this->checkAccount($server->account_id)) return $this->checkAccountFail();

        $requestInput = $request->all();
        $validator = Validator::make($requestInput, $this->validationRules);

        $requestInput['created_by_user_id'] = Auth::user()->id;

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => array(
                    'errors' => $validator->failed()
                )
            ]);
        }

        $newIPBan = IPBan::create($requestInput);
/*
        $c = curl_init('http://localhost:7869/api/ipban');
        curl_setopt_array($c, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => ('server_id=' . $newServer->id . '&operation=server.update')
        ));
        curl_exec($c);
*/
        return response()->json([
            'success' => true,
            'data' => $newIPBan
        ]);
    }

    public function update(Request $request, $id)
    {
	    //if(!$this->checkAccount($server->account_id)) return $this->checkAccountFail();

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

        $newIPBan->update($requestInput);
/*
        $c = curl_init('http://localhost:7869/api/ipban');
        curl_setopt_array($c, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => ('server_id=' . $id . '&operation=server.update')
        ));
        curl_exec($c);
*/
        return response()->json([
            'success' => true,
            'data' => $newServer
        ]);
    }

    public function destroy($id)
    {
        //if(!$this->checkAccount($server->account_id)) return $this->checkAccountFail();

        IPBan::destroy($id);

/*
        $c = curl_init('http://localhost:7869/api/ipban');
        curl_setopt_array($c, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => ('server_id=' . $id . '&operation=server.update')
        ));
        curl_exec($c);
*/
        return response()->json([
            'success' => true,
            'data' => array('id' => (int) $id, 'deleted' => true)
        ]);
    }

}
