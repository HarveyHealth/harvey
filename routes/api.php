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

Route::group(['prefix' => 'v1'], function () {
    Route::post('users', 'API\V1\UsersController@create')->name('users.create');

    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('tests/{test}', 'API\V1\TestsController@show')->name('tests.show');
        Route::post('tests/{test}/results', 'API\V1\TestsController@results')->name('test.results');

        Route::get('users', 'API\V1\UsersController@index')->name('users.index');
        Route::get('users/{user}', 'API\V1\UsersController@show')->name('users.show');
        Route::patch('users/{user}', 'API\V1\UsersController@update')->name('users.update');

        Route::get('patients', 'API\V1\PatientsController@index')->name('patients.index');
        Route::get('patients/{patient}', 'API\V1\PatientsController@show')->name('patients.show');
        Route::patch('patients/{patient}', 'API\V1\PatientsController@update')->name('patients.update');

        Route::get('appointments', 'API\V1\AppointmentsController@index')->name('appointments.index');
        Route::get('appointments/{appointment}', 'API\V1\AppointmentsController@show')->name('appointments.show');
        Route::post('appointments', 'API\V1\AppointmentsController@store')->name('appointments.store');
        Route::patch('appointments/{appointment}', 'API\V1\AppointmentsController@update')->name('appointments.update');
        Route::delete('appointments/{appointment}', 'API\V1\AppointmentsController@delete')->name('appointments.delete');

        Route::get('practitioners', 'API\V1\PractitionerController@index')->name('practitioner.index');
        Route::get('practitioners/{practitioner}', 'API\V1\PractitionerController@show')->name('practitioner.show');

        Route::get('practitioner/{practitioner}/schedule', 'API\V1\PractitionerScheduleController@show')->name('practitioner-schedule.show');
        Route::patch('practitioner/{practitioner}/schedule', 'API\V1\PractitionerScheduleController@update')->name('practitioner-schedule.update');
    });
});
