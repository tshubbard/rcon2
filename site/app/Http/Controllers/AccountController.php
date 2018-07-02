<?php

namespace App\Http\Controllers;

use App\Account;
use Illuminate\Http\Request;
use Validator;
use Auth;

class AccountController extends Controller
{
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
     * @param  string  $accountName The account name to return
     * @return \Illuminate\Http\Response
     */
    public function showJSON(Request $request, $accountName)
    {
        $account = Account::where('name' , '=', $accountName)->first();
        // weird way to populate account users
        $account->users;

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
        $validator = Validator::make($requestInput, $this->validationRules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => array(
                    'errors' => $validator->failed()
                )
            ]);
        }

        $acct = Account::create($requestInput);
        $user = Auth::user();
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

        $acct = Account::find($account->id);
        $acct->update($requestInput);
        $user = Auth::user();

        return response()->json([
            'record' => $acct,
            'accounts' => $user->accounts
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function destroy(Account $account)
    {
        Account::destroy($account->id);

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
