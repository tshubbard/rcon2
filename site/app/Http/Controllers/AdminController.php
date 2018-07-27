<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use \App\User;
use \App\Account;
use Validator;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.index');
    }

    /**
     * API - Send the admin index data in json
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function indexJSON(Request $request)
    {
        $viewData = $this->_getDefaultViewData();
        return response()->json($viewData);
    }

    /**
     * Displays the list of all Users in a table
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function usersIndex()
    {
        return view('admin.users.index');
    }

    /**
     * API - Send the admin users data in json
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function usersIndexJSON(Request $request)
    {
        $viewData = array_merge(
            $this->_getDefaultViewData(),
            array('users' => User::all())
        );
        return response()->json($viewData);
    }

    /**
     * Returns any data we want available on any view
     *
     * @return array
     */
    private function _getDefaultViewData()
    {
        $viewData = array(
            'thisUser' => Auth::user(),
            'bodyCssClass' => 'admin-body'
        );

        return $viewData;
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
        $requestInput = $request->all();
        $validator = Validator::make($requestInput, array(
            'role_id' => 'required|numeric'
        ));

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => array(
                    'errors' => $validator->failed()
                )
            ]);
        }

        $user = User::find($id);
        $user->role_id = $requestInput['role_id'];
        $user->save();

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($userId)
    {
        User::destroy($userId);

        return response()->json([
            'success' => true,
            'data' => $userId
        ]);
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
}
