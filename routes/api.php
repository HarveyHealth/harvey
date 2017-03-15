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
    Route::get('patients/{patient}/appointments', 'API\alpha\PatientsController@appointments');
    Route::resource('patients', 'API\alpha\PatientsController');
    Route::resource('appointments', 'API\alpha\AppointmentsController');
    Route::post('tests/{test}/results', 'API\alpha\TestsController@results');
    Route::resource('tests', 'API\alpha\TestsController');
});

Route::group(['prefix' => 'v1', 'middleware' => 'auth:api'], function () {
    Route::resource('users', 'API\V1\UsersController');
    Route::get('patients/{patient}/appointments', 'API\V1\PatientsController@appointments');
    Route::resource('patients', 'API\V1\PatientsController');
    Route::resource('appointments', 'API\v1\AppointmentsController');
});
