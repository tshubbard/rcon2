<?php

namespace App\Http\Controllers;

use App\Account;
use App\User;
use App\Server;
use Illuminate\Http\Request;
use Validator;
use Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class AccountController extends Controller
{
    protected $errorMsgOwnerTriedToLeave = 'User is the Owner of the account they attempted to leave.';
    protected $errorMsgUserNotMember = 'User does not belong to Account they are attempting to leave.';
    protected $errorMsgNotOwnerTriedToRemoveUser = 'Must be Account Owner to remove a User from Account';
    protected $errorMsgNotOwnerTriedToRemoveServer = 'Must be Account Owner to remove a Server from Account';
    protected $logMsgOwnerTriedToLeave = 'Account Owner tried to Leave';
    protected $logMsgUserNotMember = 'User does not belong to Account they are attempting to leave';
    protected $logMsgNotOwnerTriedToRemoveUser = 'Non-Account Owner tried to remove a User from the Account';
    protected $logMsgNotOwnerTriedToRemoveServer = 'Non-Account Owner tried to remove a Server from the Account';

    protected $validationRules = array(
        'name' => 'required',
    );

    /**
     * Display the specified resource.
     *
     * @param  \App\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function show(Account $account)
    {
        return view('accounts.show', compact('account'));
    }

    /**
     * API - Display the specified Account in JSON
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|numeric  $accountSlug The account slug or id to return
     * @return \Illuminate\Http\Response
     */
    public function showJSON(Request $request, $accountSlug)
    {
        if (is_numeric($accountSlug)) {
            $field = 'id';
        } else {
            $field = 'slug';
        }
        $account = Account::where($field , '=', $accountSlug)->first();

        if(!$this->checkAccount($account->id)) return $this->checkAccountFail();

        // weird way to populate account users
        $account->users;
        foreach($account->users as &$user) {
            $user->role_name = $user->role->name;
        }
        $account->servers;

        return response()->json($account);
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
        $validationRules = array_merge($this->validationRules, array(
            'owner_id' => 'required|numeric'
        ));
        $validator = Validator::make($requestInput, $validationRules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => array(
                    'errors' => $validator->failed()
                )
            ]);
        }

        $user = Auth::user();

        $requestInput['slug'] = str_slug($requestInput['name']);
        $requestInput['owner_id'] = $user->id;

        $acct = Account::create($requestInput);
        $user->accounts()->attach($acct->id);

        return response()->json([
            'record' => $acct,
            'accounts' => $user->accounts
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Account $account)
    {
        $requestInput = $request->all();
        $validator = Validator::make($requestInput, $this->validationRules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->failed()
            ]);
        }

        $user = Auth::user();

        $requestInput['slug'] = str_slug($requestInput['name']);

        // don't update the owner_id if it is sent
        unset($requestInput['owner_id']);

        if(!$this->checkAccount($account->id)) return $this->checkAccountFail();

        $account->update($requestInput);

        return response()->json([
            'record' => $account,
            'accounts' => $user->accounts
        ]);
    }

    public function updateUser(Request $request, Account $account, User $user) {
        if(!$this->checkAccount($account->id)) return $this->checkAccountFail();

        $success = false;
        $msg = 'Not an active endpoint yet!';

        // todo: update any user stuff on the account that we need to do

        return response()->json([
            'success' => $success,
            'record' => $user,
            'message' => $msg
        ]);
    }

    public function checkUser(Request $request, Account $account, $userEmail) {
        if(!$this->checkAccount($account->id)) return $this->checkAccountFail();

        $user = User::where('email', '=', $userEmail)->first();

        $success = true;
        $msg = '';
        $accts = $user->accounts;
        foreach($accts as $acct) {
            if ($acct['id'] === $account->id) {
                $success = false;
                $msg = 'User already exists on Account';
            }
        }

        return response()->json([
            'success' => $success,
            'record' => $user,
            'message' => $msg
        ]);
    }

    public function addUser(Request $request, Account $account, User $user) {
        if(!$this->checkAccount($account->id)) return $this->checkAccountFail();

        $addAcct = true;
        $success = false;
        $msg = '';
        $accts = $user->accounts;
        foreach($accts as $acct) {
            if ($acct['id'] === $account->id) {
                $addAcct = false;
                $msg = 'User already exists on Account';
            }
        }
        if ($addAcct) {
            $user->accounts()->attach($account->id);
            $success = true;
        }
        return response()->json([
            'success' => $success,
            'record' => $user,
            'message' => $msg
        ]);
    }

    public function removeUser(Request $request, Account $account, User $user) {
        $thisUser = Auth::user();

        if ($account->owner_id != $thisUser->id) {
            Log::error($this->logMsgNotOwnerTriedToRemoveUser, [
                'controller' => 'AccountController',
                'function' => 'leave',
                'this_user_id' => $thisUser->id,
                'this_user_name' => $thisUser->name,
                'user_id' => $user->id,
                'user_name' => $user->name,
                'account_id' => $account->id,
                'account_name' => $account->name,
            ]);

            return response()->json([
                'success' => false,
                'errors' => [
                    $this->errorMsgNotOwnerTriedToRemoveUser
                ]
            ], 405);
        }

        if(!$this->checkAccount($account->id)) return $this->checkAccountFail();

        $user->accounts()->detach($account->id);
        return response()->json([
            'success' => true,
            'record' => $user
        ]);
    }

    public function removeServer(Request $request, Account $account, Server $server) {
        $thisUser = Auth::user();

        if ($account->owner_id != $thisUser->id) {
            Log::error($this->logMsgNotOwnerTriedToRemoveServer, [
                'controller' => 'AccountController',
                'function' => 'leave',
                'this_user_id' => $thisUser->id,
                'this_user_name' => $thisUser->name,
                'server_id' => $server->id,
                'server_name' => $server->name,
                'account_id' => $account->id,
                'account_name' => $account->name,
            ]);

            return response()->json([
                'success' => false,
                'errors' => [
                    $this->errorMsgNotOwnerTriedToRemoveServer
                ]
            ], 405);
        }

        $server->delete();

        return response()->json([
            'success' => true,
            'record' => $server
        ]);
    }

    public function leave(Request $request, Account $account)
    {
        $user = Auth::user();

        // Make sure User exists as a member of the Account
        if ($account->users()->where('user_id', '=', $user->id)->exists()) {
            // Make sure User is not the Owner of the Account
            if ($account->owner_id === $user->id) {
                Log::error($this->logMsgOwnerTriedToLeave, [
                    'controller' => 'AccountController',
                    'function' => 'leave',
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'account_id' => $account->id,
                    'account_name' => $account->name,
                ]);

                return response()->json([
                    'success' => false,
                    'errors' => [
                        $this->errorMsgOwnerTriedToLeave
                    ]
                ], 405);
            } else {
                // otherwise remove the account from the user
                $user->accounts()->detach($account->id);
                return response()->json([
                    'success' => true,
                    'record' => $account,
                    'accounts' => $user->accounts
                ]);
            }
        } else {
            Log::error($this->logMsgUserNotMember, [
                'controller' => 'AccountController',
                'function' => 'leave',
                'user_id' => $user->id,
                'user_name' => $user->name,
                'account_id' => $account->id,
                'account_name' => $account->name,
            ]);

            return response()->json([
                'success' => false,
                'errors' => [
                    $this->errorMsgUserNotMember
                ]
            ], 405);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function destroy(Account $account)
    {
        if(!$this->checkAccount($account->id)) return $this->checkAccountFail();

        $account->delete();
        $account->users()->detach();

        return response()->json($account);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function edit(Account $account)
    {
        //
    }
}
