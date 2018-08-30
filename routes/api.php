<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'namespace' => 'Auth'
        ], function () {
#Login
    Route::post('auth/login', 'AuthController@login');
#Temp Sign Up
    Route::post('auth/signup', 'AuthController@signup');

    Route::group([
        'middleware' => ['auth:api', 'dyna_connect']
            ], function() {
#Logout
        Route::get('auth/logout', 'AuthController@logout');
		});
});
