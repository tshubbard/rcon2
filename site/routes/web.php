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
Route::get('email-verification/error', 'Auth\RegisterController@getVerificationError')->name('email-verification.error');
Route::get('email-verification/check/{token}', 'Auth\RegisterController@getVerification')->name('email-verification.check');
Route::get('logout', 'AuthController@logout');

// Requires User Authentication
Route::group(['middleware' => ['auth']], function () {
    // AccountController
    Route::get('/a/{account}', 'AccountController@show');
    Route::get('/api/v1/a/{account}', 'AccountController@showJSON');
    Route::post('/api/v1/accounts', 'AccountController@store');
    Route::post('/api/v1/accounts/{account}/leave', 'AccountController@leave');
    Route::post('/api/v1/accounts/{account}/removeUser/{user}', 'AccountController@removeUser');
    Route::post('/api/v1/accounts/{account}/removeServer/{server}', 'AccountController@removeServer');
    Route::put('/api/v1/accounts/{account}', 'AccountController@update');
    Route::delete('/api/v1/accounts/{account}', 'AccountController@destroy');

    // DashboardController
    Route::get('/dashboard/', 'DashboardController@index');
    Route::get('/dashboard/{serverId}', 'DashboardController@show');

    // ItemController
    Route::post('/api/v1/items', 'ItemController@index');

    // ServerController
    Route::get('/api/v1/server/{serverId}', 'ServerController@show');
    Route::get('/api/v1/s/{serverId}', 'ServerController@showJSON');
    Route::get('/api/v1/servers', 'ServerController@serversIndexJSON');

    Route::post('/api/v1/server', 'ServerController@store');
    Route::put('/api/v1/server/{serverId}', 'ServerController@update');
    Route::delete('/api/v1/server/{serverId}', 'ServerController@destroy');

    // ServerEventController
    Route::get('/api/v1/serverEvents', 'ServerEventController@eventsList');
    Route::get('/api/v1/serverEvents/{serverEvent}', 'ServerEventController@show');
    Route::post('/api/v1/serverEvents', 'ServerEventController@store');
    Route::put('/api/v1/serverEvents/{serverEvent}', 'ServerEventController@update');
    Route::put('/api/v1/serverEvents/{serverEvent}/active/{isActive}', 'ServerEventController@updateActive');
    Route::delete('/api/v1/serverEvents/{serverEvent}', 'ServerEventController@destroy');

    // PlayerController
    Route::get('/api/v1/players/{server}', 'PlayerController@index');
    Route::get('/api/v1/players/{server}/current', 'PlayerController@current');
    Route::post('/api/v1/players/{player}/kick', 'PlayerController@kick');
    Route::post('/api/v1/players/{player}/ban', 'PlayerController@ban');
    Route::delete('/api/v1/players/{player}', 'PlayerController@destroy');

    // PlayerController
    Route::get('/api/v1/chat/{server}', 'ChatController@index');

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
    Route::put('/api/v1/admin/users/{userId}', 'AdminController@update');
    Route::delete('/api/v1/admin/users/{userId}', 'AdminController@destroy');
});
