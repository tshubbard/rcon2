<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use \App\User;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$viewData = $this->_getDefaultViewData();
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
        /*
        $viewData = array_merge(
            $this->_getDefaultViewData(),
            array('users' => User::all())
        );
        */
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
