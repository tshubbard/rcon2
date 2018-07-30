<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function checkAccount($account_ids)
    {
        if(!is_array($account_ids))
            $account_ids = array($account_ids);

        $user = Auth::user();
        $accounts = $user->accounts;
        $account_valid = false;

        foreach($accounts as $row)
            if(in_array($row['id'], $account_ids))
                return true;

        return false;
    }

    public function checkAccountFail()
    {
        return response()->json([
            'success' => false,
            'data' => array(
                'errors' => 'Invalid account permissions.'
            )
        ]);
    }
}
