<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// User's login route
Route::post('login', 'API\UserController@userLogin');

// User register route
Route::post('register', 'API\UserController@registerUser');

// Get single user
Route::get('users/{id}', 'API\UserController@getUser');

//Get all users
Route::get('users', 'API\UserController@getAllUsers');

// Transaction routes
Route::post('transfer', 'API\TransferController@transferMoney');
Route::get('transactions', 'API\TransferController@listTransactions');


