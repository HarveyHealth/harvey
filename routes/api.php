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

Route::group(['prefix' => 'alpha', 'middleware' => 'auth:api'], function () {
    Route::resource('users', 'API\alpha\UsersController');
    Route::resource('appointments', 'API\alpha\AppointmentsController');
    Route::post('tests/{test}/results', 'API\alpha\TestsController@results');
    Route::resource('tests', 'API\alpha\TestsController');
});

Route::group(['prefix' => 'v1', 'middleware' => 'auth:api'], function () {
    Route::get('tests/{test}', 'API\V1\TestsController@show');
    Route::post('tests/{test}/results', 'API\V1\TestsController@results');
    
    Route::get('users/{user}', 'API\V1\UsersController@show');
    Route::patch('users/{user}', 'API\V1\UsersController@update');
    
    Route::get('patients/{patient}', 'API\V1\PatientsController@show');
    Route::patch('patients/{patient}', 'API\V1\PatientsController@update');
    
    Route::get('appointments', 'API\V1\AppointmentsController@index');
    Route::post('appointments', 'API\V1\AppointmentsController@store');
    
});
