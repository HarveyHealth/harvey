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
    Route::post('visitors/send_email', 'VisitorsController@sendEmail')->name('visitors.send-email');
    Route::get('lab/tests/information', 'LabTestsController@information')->name('lab-tests.information');

    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('users', 'UsersController@index')->name('users.index');
        Route::get('users/{user}', 'UsersController@show')->name('users.show');
        Route::patch('users/{user}', 'UsersController@update')->name('users.update');
        Route::post('users/{user}/image', 'UsersController@profileImageUpload')->name('users.profile-image-upload');
        Route::get('users/{user}/phone/verify', 'UsersController@phoneVerify')->name('users.phoneVerify');
        Route::post('users/{user}/phone/send_verification_code', 'UsersController@sendVerificationCode')->name('users.sendVerificationCode');
        Route::delete('users/{user}/cards/{card_id}', 'UsersController@deleteCard')->name('users.delete-card');
        Route::get('users/{user}/cards', 'UsersController@getCards')->name('users.get-cards');
        Route::get('users/{user}/cards/{card_id}', 'UsersController@getCard')->name('users.get-card');
        Route::patch('users/{user}/cards/{card_id}', 'UsersController@updateCard')->name('users.update-card');
        Route::post('users/{user}/cards', 'UsersController@addCard')->name('users.add-card');

        Route::get('patients', 'PatientsController@getAll')->name('patients.get-all');
        Route::get('patients/{patient}', 'PatientsController@get')->name('patients.get');
        Route::patch('patients/{patient}', 'PatientsController@update')->name('patients.update');

        Route::get('patients/{patient}/attachments', 'PatientsController@getAttachments')->name('patient.attachments.get');
        Route::get('patients/{patient}/attachments/{attachment}', 'PatientsController@getAttachment')->name('patient.attachment.get');
        Route::post('patients/{patient}/attachments', 'PatientsController@storeAttachment')->name('patient.attachment.store');
        Route::delete('patients/{patient}/attachments/{attachment}', 'PatientsController@deleteAttachment')->name('patient.attachment.delete');

        Route::get('patients/{patient}/prescriptions', 'PatientsController@getPrescriptions')->name('patient.prescriptions.get');
        Route::get('patients/{patient}/prescriptions/{prescription}', 'PatientsController@getPrescription')->name('patient.prescription.get');
        Route::post('patients/{patient}/prescriptions', 'PatientsController@storePrescription')->name('patient.prescription.store');
        Route::delete('patients/{patient}/prescriptions/{prescription}', 'PatientsController@deletePrescription')->name('patient.prescription.delete');

        Route::get('patients/{patient}/soap_notes', 'PatientsController@getSoapNotes')->name('patient.soap_notes.get');
        Route::get('patients/{patient}/soap_notes/{soap_note}', 'PatientsController@getSoapNote')->name('patient.soap_note.get');
        Route::post('patients/{patient}/soap_notes', 'PatientsController@storeSoapNote')->name('patient.soap_note.store');
        Route::delete('patients/{patient}/soap_notes/{soap_note}', 'PatientsController@deleteSoapNote')->name('patient.soap_note.delete');

        Route::get('appointments', 'AppointmentsController@index')->name('appointments.index');
        Route::get('appointments/{appointment}', 'AppointmentsController@show')->name('appointments.show');
        Route::post('appointments', 'AppointmentsController@store')->name('appointments.store');
        Route::patch('appointments/{appointment}', 'AppointmentsController@update')->name('appointments.update');
        Route::delete('appointments/{appointment}', 'AppointmentsController@delete')->name('appointments.delete');

        Route::get('practitioners', 'PractitionersController@index')->name('practitioner.index');
        Route::get('practitioners/{practitioner}', 'PractitionersController@show')->name('practitioner.show');
        Route::patch('practitioners/{practitioner}', 'PractitionersController@update')->name('practitioner.update');
        Route::post('practitioners/{practitioner}/profile-image', 'PractitionersController@profileImageUpload')->name('practitioners.profile-image-upload');
        Route::post('practitioners/{practitioner}/bg-image', 'PractitionersController@backgroundImageUpload')->name('practitioners.bg-image-upload');

        Route::get('practitioner/{practitioner}/schedule', 'PractitionerScheduleController@show')->name('practitioner-schedule.show');
        Route::patch('practitioner/{practitioner}/schedule', 'PractitionerScheduleController@update')->name('practitioner-schedule.update');

        Route::get('messages', 'MessagesController@index')->name('messages.index');
        Route::get('messages/{message}', 'MessagesController@show')->name('messages.show');
        Route::post('messages', 'MessagesController@new')->name('messages.new');
        Route::put('messages/{message}/read', 'MessagesController@read')->name('messages.read');
        Route::delete('messages/{message}', 'MessagesController@delete')->name('messages.delete');

        Route::get('lab/tests', 'LabTestsController@getAll')->name('lab-tests.get-all');
        Route::get('lab/tests/{labTest}', 'LabTestsController@get')->name('lab-tests.get');
        Route::post('lab/tests', 'LabTestsController@store')->name('lab-tests.store');
        Route::patch('lab/tests/{labTest}', 'LabTestsController@update')->name('lab-tests.update');
        Route::delete('lab/tests/{labTest}', 'LabTestsController@delete')->name('lab-tests.delete');
        Route::get('lab/tests/{labTest}/results', 'LabTestsController@getResults')->name('lab-tests.get-results');
        Route::post('lab/tests/{labTest}/results', 'LabTestsController@storeResult')->name('lab-tests.store-result');
        Route::delete('lab/tests/{labTest}/results/{labTestResult}', 'LabTestsController@deleteResult')->name('lab-tests.delete-result');

        Route::get('lab/orders', 'LabOrdersController@getAll')->name('lab-orders.get-all');
        Route::get('lab/orders/{labOrder}', 'LabOrdersController@get')->name('lab-orders.get');
        Route::post('lab/orders', 'LabOrdersController@store')->name('lab-orders.store');
        Route::patch('lab/orders/{labOrder}', 'LabOrdersController@update')->name('lab-orders.update');
        Route::delete('lab/orders/{labOrder}', 'LabOrdersController@delete')->name('lab-orders.delete');
    });
});
