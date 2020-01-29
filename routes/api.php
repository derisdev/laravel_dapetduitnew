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

Route::group(['prefix' => 'v1', 'middleware' => 'cors'], function(){
    Route::resource('payment', 'PaymentController', [
        'except' => ['create', 'edit']
    ]);
    
    Route::get('/user/payment/{phone}', [
        'uses' => 'PaymentController@readForUser'
    ]);

    Route::post('/user/register', [
        'uses' => 'AuthController@store'
    ]);
    
    Route::post('/user/signin', [
        'uses' => 'AuthController@signin'
    ]);
    
    Route::post('/user/phone_verify', [
        'uses' => 'PhoneController@store'
    ]);

    Route::resource('refferal', 'RefferalController', [
        'except' => ['create', 'edit', 'index', 'destroy']
    ]);

    Route::resource('rewards', 'RewardsController', [
        'except' => ['create', 'edit', 'index', 'destroy']
    ]);

    Route::resource('notif', 'NotifController', [
        'except' => ['create', 'edit']
    ]);

    Route::resource('feedback', 'FeedbackController', [
        'except' => ['create', 'edit']
    ]);

    Route::resource('question', 'QuestionController', [
        'except' => ['create', 'edit']
    ]);

});