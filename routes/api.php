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

Route::group(['prefix' => 'v1', 'namespace' => 'API\V1'], function () {
    Route::post('users', 'UsersController@create')->name('users.create');

    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('tests/{test}', 'TestsController@show')->name('tests.show');
        Route::post('tests/{test}/results', 'TestsController@results')->name('test.results');

        Route::get('users', 'UsersController@index')->name('users.index');
        Route::get('users/{user}', 'UsersController@show')->name('users.show');
        Route::patch('users/{user}', 'UsersController@update')->name('users.update');

        Route::get('patients/{patient}', 'PatientsController@show')->name('patients.show');
        Route::patch('patients/{patient}', 'PatientsController@update')->name('patients.update');

        Route::get('appointments', 'AppointmentsController@index')->name('appointments.index');
        Route::get('appointments/{appointment}', 'AppointmentsController@show')->name('appointments.show');
        Route::post('appointments', 'AppointmentsController@store')->name('appointments.store');
        Route::patch('appointments/{appointment}', 'AppointmentsController@update')->name('appointments.update');
        Route::delete('appointments/{appointment}', 'AppointmentsController@delete')->name('appointments.delete');

        Route::get('practitioners/{practitioner}', 'PractitionerController@show')->name('practitioner.show');

        Route::get('practitioner/{practitioner}/schedule', 'PractitionerScheduleController@show')->name('practitioner-schedule.show');
        Route::patch('practitioner/{practitioner}/schedule', 'PractitionerScheduleController@update')->name('practitioner-schedule.update');
    });
});
