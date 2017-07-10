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
    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('tests/{test}', 'TestsController@show')->name('tests.show');
        Route::post('tests/{test}/results', 'TestsController@results')->name('test.results');

        Route::get('users', 'UsersController@index')->name('users.index');
        Route::get('users/{user}', 'UsersController@show')->name('users.show');
        Route::patch('users/{user}', 'UsersController@update')->name('users.update');
        Route::post('users/{user}/image', 'UsersController@imageUpload')->name('users.image-upload');

        Route::get('patients', 'PatientsController@index')->name('patients.index');
        Route::get('patients/{patient}', 'PatientsController@show')->name('patients.show');
        Route::patch('patients/{patient}', 'PatientsController@update')->name('patients.update');

        Route::get('appointments', 'AppointmentsController@index')->name('appointments.index');
        Route::get('appointments/{appointment}', 'AppointmentsController@show')->name('appointments.show');
        Route::post('appointments', 'AppointmentsController@store')->name('appointments.store');
        Route::patch('appointments/{appointment}', 'AppointmentsController@update')->name('appointments.update');
        Route::delete('appointments/{appointment}', 'AppointmentsController@delete')->name('appointments.delete');

        Route::get('practitioners', 'PractitionersController@index')->name('practitioner.index');
        Route::get('practitioners/{practitioner}', 'PractitionersController@show')->name('practitioner.show');
        Route::post('practitioners/{practitioner}/image', 'PractitionersController@imageUpload')->name('practitioners.image-upload');

        Route::get('practitioner/{practitioner}/schedule', 'PractitionerScheduleController@show')->name('practitioner-schedule.show');
        Route::patch('practitioner/{practitioner}/schedule', 'PractitionerScheduleController@update')->name('practitioner-schedule.update');
        

        Route::get('messages', 'MessagesController@index')->name('messages.index');
        Route::get('messages/{message}', 'MessagesController@show')->name('messages.show');
        Route::post('messages', 'MessagesController@new')->name('messages.new');
        Route::put('messages/{message}/read', 'MessagesController@read')->name('messages.read');
        Route::delete('messages/{message}', 'MessagesController@delete')->name('messages.delete');

        Route::get('lab/tests', 'LabTestsController@index')->name('lab-tests.index');
        Route::get('lab/tests/{labTest}', 'LabTestsController@show')->name('lab-tests.show');
        Route::post('lab/tests', 'LabTestsController@store')->name('lab-tests.store');
        Route::patch('lab/tests/{labTest}', 'LabTestsController@update')->name('lab-tests.update');
        Route::delete('lab/tests/{labTest}', 'LabTestsController@delete')->name('lab-tests.delete');

        Route::get('lab/orders', 'LabOrdersController@index')->name('lab-orders.index');
        Route::get('lab/orders/{labOrder}', 'LabOrdersController@show')->name('lab-orders.show');
        Route::post('lab/orders', 'LabOrdersController@store')->name('lab-orders.store');
        Route::patch('lab/orders/{labOrder}', 'LabOrdersController@update')->name('lab-orders.update');
        Route::delete('lab/orders/{labOrder}', 'LabOrdersController@delete')->name('lab-orders.delete');
    });
    
    Route::post('users', 'UsersController@create')->name('users.create');
    Route::get('lab/tests/information', 'LabTestsController@information')->name('lab-tests.information');Route::post('users', 'UsersController@create')->name('users.create');
    Route::get('lab/tests/information', 'LabTestsController@information')->name('lab-tests.information');
});
