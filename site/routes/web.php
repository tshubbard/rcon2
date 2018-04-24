<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

// Requires User Authentication
Route::group(['middleware' => ['auth']], function () {
    // AccountController
    Route::get('/a/{account}', 'AccountController@show');
    Route::get('/api/v1/a/{account}', 'AccountController@showJSON');

    // DashboardController
    Route::get('/dashboard/', 'DashboardController@index');
    Route::get('/dashboard/{serverId}', 'DashboardController@show');

    Route::get('/api/v1/user/servers', 'DashboardController@servers');

    // ItemController
    Route::get('/api/v1/items', 'ItemController@index');

    // ServerController
    Route::get('/api/v1/server/{serverId}', 'ServerController@show');
    Route::post('/api/v1/server', 'ServerController@store');
    Route::put('/api/v1/server/{serverId}', 'ServerController@update');
    Route::delete('/api/v1/server/{serverId}', 'ServerController@destroy');

    // ServerEventController
    Route::get('/api/v1/serverEvents/{serverId}', 'ServerEventController@index');
    Route::get('/api/v1/serverEvent/{serverEvent}', 'ServerEventController@show');
    Route::post('/api/v1/serverEvent', 'ServerEventController@store');
    Route::put('/api/v1/serverEvent/{serverEvent}', 'ServerEventController@update');
    Route::delete('/api/v1/serverEvent/{serverEvent}', 'ServerEventController@destroy');

    // UserController
    Route::get('/u/{userName}', 'UserController@show');
    Route::get('/api/v1/u/{userName}', 'UserController@showJSON');
    Route::get('/api/v1/user/me', 'UserController@showMeJSON');
});

// Requires Admin Authentication
Route::group(['middleware' => ['admin']], function () {
    Route::get('/admin', 'AdminController@index');
    Route::get('/api/v1/admin', 'AdminController@indexJSON');

    Route::get('/admin/users', 'AdminController@usersIndex');
    Route::get('/api/v1/admin/users', 'AdminController@usersIndexJSON');

    Route::get('/api/v1/admin/servers', 'AdminController@serversIndexJSON');
});
