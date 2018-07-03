<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Auth;

class UserController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param  string  $userName The user name of a user to return
     * @return \Illuminate\Http\Response
     */
    public function show($userName)
    {
        $user = User::where('name' , '=', $userName)->first();
        $accounts = $user->accounts;
        return view('users.show', compact('user', 'accounts'));
    }

    /**
     * API - Display the specified User in JSON
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|numeric  $userSlug The user slug or id of a user to return
     * @return \Illuminate\Http\Response
     */
    public function showJSON(Request $request, $userSlug)
    {
        if (is_numeric($userSlug)) {
            $field = 'id';
        } else {
            $field = 'slug';
        }
        $user = User::where($field , '=', $userSlug)->first();
        // weird way to populate user accounts
        $user->accounts;

        return response()->json($user);
    }

    /**
     * API - Display the current User in JSON
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $userName The user name of a user to return
     * @return \Illuminate\Http\Response
     */
    public function showMeJSON(Request $request)
    {
        $user = Auth::user();
        // weird way to populate user accounts
        $user->accounts;

        return response()->json($user);
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
