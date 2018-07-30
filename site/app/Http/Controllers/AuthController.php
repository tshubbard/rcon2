<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;

class AuthController extends Controller
{
    protected function logout(Request $request)
    {
        Auth::logout();

        $request->session()->flush();

        return redirect('/');
    }
}
