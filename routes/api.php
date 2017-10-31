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
    Route::get('visitors/verifications/zip/{zip}', 'ZipVerificationController@getInfo');

    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('discountcode', 'DiscountCodesController@index')->name('discountcodes.index');
        Route::get('tests/{test}', 'TestsController@show')->name('tests.show');
        Route::post('tests/{test}/results', 'TestsController@results')->name('test.results');

        Route::get('users', 'UsersController@index')->name('users.index');
        Route::get('users/{user}', 'UsersController@show')->name('users.show');
        Route::patch('users/{user}', 'UsersController@update')->name('users.update');
        Route::post('users/{user}/image', 'UsersController@profileImageUpload')->name('users.profile-image-upload');
        Route::get('users/{user}/phone/verify', 'UsersController@phoneVerify')->name('users.phoneVerify');
        Route::post('users/{user}/phone/send_verification_code', 'UsersController@sendVerificationCode')->name('users.sendVerificationCode');
        Route::delete('users/{user}/cards/{cardId}', 'UsersController@deleteCard')->name('users.delete-card');
        Route::get('users/{user}/cards', 'UsersController@getCards')->name('users.get-cards');
        Route::get('users/{user}/cards/{cardId}', 'UsersController@getCard')->name('users.get-card');
        Route::patch('users/{user}/cards/{cardId}', 'UsersController@updateCard')->name('users.update-card');
        Route::post('users/{user}/cards', 'UsersController@addCard')->name('users.add-card');

        Route::get('patients', 'PatientsController@getAll')->name('patients.get-all');
        Route::get('patients/{patient}', 'PatientsController@getOne')->name('patients.get-one');
        Route::patch('patients/{patient}', 'PatientsController@update')->name('patients.update');

        Route::get('attachments', 'AttachmentsController@getAll')->name('attachments.get');
        Route::get('attachments/{attachment}', 'AttachmentsController@getOne')->name('attachment.get-one');
        Route::post('patients/{patient}/attachments', 'AttachmentsController@store')->name('attachment.store');
        Route::delete('attachments/{attachment}', 'AttachmentsController@delete')->name('attachment.delete');

        Route::get('prescriptions', 'PrescriptionsController@getAll')->name('prescriptions.get-all');
        Route::get('prescriptions/{prescription}', 'PrescriptionsController@getOne')->name('prescriptions.get-one');
        Route::post('patients/{patient}/prescriptions', 'PrescriptionsController@store')->name('prescriptions.store');
        Route::delete('prescriptions/{prescription}', 'PrescriptionsController@delete')->name('prescriptions.delete');

        Route::get('soap_notes', 'SoapNotesController@getAll')->name('soap_notes.get-all');
        Route::get('soap_notes/{soapNote}', 'SoapNotesController@getOne')->name('soap_notes.get-one');
        Route::post('patients/{patient}/soap_notes', 'SoapNotesController@store')->name('soap_notes.store');
        Route::delete('soap_notes/{soapNote}', 'SoapNotesController@delete')->name('soap_notes.delete');

        Route::get('intakes/{typeformToken}', 'IntakesController@getOne')->name('intakes.get-one');

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

        Route::get('lab/tests', 'LabTestsController@index')->name('lab-tests.index');
        Route::get('lab/tests/information', 'LabTestsController@information')->name('lab-tests.information');
        Route::get('lab/tests/{labTest}', 'LabTestsController@show')->name('lab-tests.show');
        Route::post('lab/tests', 'LabTestsController@store')->name('lab-tests.store');
        Route::patch('lab/tests/{labTest}', 'LabTestsController@update')->name('lab-tests.update');
        Route::delete('lab/tests/{labTest}', 'LabTestsController@delete')->name('lab-tests.delete');
        Route::get('lab/tests/{labTest}/results', 'LabTestsController@getResults')->name('lab-tests.get-results');
        Route::post('lab/tests/{labTest}/results', 'LabTestsController@storeResult')->name('lab-tests.store-result');
        Route::delete('lab/tests/{labTest}/results/{labTestResult}', 'LabTestsController@deleteResult')->name('lab-tests.delete-result');

        Route::get('lab/orders', 'LabOrdersController@getAll')->name('lab-orders.get-all');
        Route::get('lab/orders/{labOrder}', 'LabOrdersController@getOne')->name('lab-orders.get-one');
        Route::post('lab/orders', 'LabOrdersController@store')->name('lab-orders.store');
        Route::patch('lab/orders/{labOrder}', 'LabOrdersController@update')->name('lab-orders.update');
        Route::delete('lab/orders/{labOrder}', 'LabOrdersController@delete')->name('lab-orders.delete');

        Route::get('skus', 'SkusController@index')->name('skus.index');
        Route::get('skus/lab-tests', 'SkusController@indexLabTests')->name('skus.indexLabTests');
        Route::get('skus/{sku}', 'SkusController@show')->name('skus.show');
        Route::post('skus', 'SkusController@store')->name('skus.store');
        Route::put('skus/{sku}', 'SkusController@update')->name('skus.update');
        Route::delete('skus/{sku}', 'SkusController@delete')->name('skus.delete');
        Route::patch('skus/{sku}', 'SkusController@updateListOrder')->name('skus.updateListOrder');
    });
});
