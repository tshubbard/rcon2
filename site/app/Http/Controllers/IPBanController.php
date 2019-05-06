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
        // **** Permissions Check ****
        if(!$this->checkAccount($server->account_id)) return $this->checkAccountFail();
        // ***************************

        $ipbans = DB::table('server_ipbans')
            ->select('*')
            ->where('server_id', '=', $server->id)
            ->orderBy('id')
            ->get();

        return response()->json($ipbans);
    }

    public function store(Request $request)
    {
        $requestInput = $request->all();

        // **** Permissions Check ****
        $server = Server::find($requestInput['server_id']);
        if(!$this->checkAccount($server->account_id)) return $this->checkAccountFail();
        // ***************************

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
        $c = curl_init('http://localhost:7869/api/server');
        curl_setopt_array($c, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => ('server_id=' . $newServer->id . '&operation=ipban.update')
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
        // **** Permissions Check ****
        $ipban = IPBan::find($id);
        $server = Server::find($ipban->server_id);
        if(!$this->checkAccount($server->account_id)) return $this->checkAccountFail();
        // ***************************

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

        $ipban->update($requestInput);
/*
        $c = curl_init('http://localhost:7869/api/server');
        curl_setopt_array($c, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => ('server_id=' . $id . '&operation=ipban.update')
        ));
        curl_exec($c);
*/
        return response()->json([
            'success' => true,
            'data' => $ipban
        ]);
    }

    public function destroy($id)
    {
        $ipban = IPBan::find($id);

        // **** Permissions Check ****
        $server = Server::find($ipban->server_id);
        if(!$this->checkAccount($server->account_id)) return $this->checkAccountFail();
        // ***************************

        IPBan::destroy($id);

/*
        $c = curl_init('http://localhost:7869/api/server');
        curl_setopt_array($c, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => ('server_id=' . $id . '&operation=ipban.update')
        ));
        curl_exec($c);
*/
        return response()->json([
            'success' => true,
            'data' => array('id' => (int) $id, 'deleted' => true)
        ]);
    }

}
