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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['namespace' => 'Api\Auth'], function() {
	Route::post('signup', 'UserController@signup');
	Route::post('login', 'UserController@login');
	Route::post('forgotpassword', 'UserController@forgotpassword');
	//Route::post('reset-password', 'UserController@resetPassword');
	//Route::post('facebook-login', 'UserController@facebookLogin');
	Route::group(['middleware' => ['auth:api']], function() {
		Route::get('get-profile', 'UserController@getProfile');
	});
});

Route::group(['namespace' => 'Api'], function() {
	Route::group(['middleware' => ['auth:api']], function() {
		Route::post('addeditjob', 'ApiController@addeditjob');
		Route::get('getalljobs', 'ApiController@getalljobs');
	});
});