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

    # Create User
    Route::post('users', 'UsersController@store')->name('users.store');

    # Send email to visitor (for instance, email template 'subscribe' to send Harvey eBook)
    Route::post('visitors/send_email', 'VisitorsController@sendEmail')->name('visitors.send-email');

    # Verify ZIPs
    Route::get('visitors/verifications/zip/{zip}', 'ZipVerificationController@getInfo');

    Route::group(['middleware' => 'auth:api'], function () {

        # Search
        Route::get('search', 'SearchController@search')->name('search.search');        

        # Validate Discount Codes
        Route::get('discount_codes/{code}', 'DiscountCodesController@getOne')->name('discount-codes.index');

        # Users
        Route::get('users', 'UsersController@getAll')->name('users.get-all');
        Route::get('users/{user}', 'UsersController@getOne')->name('users.get-one');
        Route::patch('users/{user}', 'UsersController@update')->name('users.update');
        Route::post('users/{user}/image', 'UsersController@profileImageUpload')->name('users.profile-image-upload');
        Route::get('users/{user}/phone/verify', 'UsersController@phoneVerify')->name('users.phone-verify');
        Route::post('users/{user}/phone/send_verification_code', 'UsersController@sendVerificationCode')->name('users.send-verification-code');
        Route::delete('users/{user}/cards/{cardId}', 'UsersController@deleteCard')->name('users.delete-card');
        Route::get('users/{user}/cards', 'UsersController@getCards')->name('users.get-cards');
        Route::get('users/{user}/cards/{cardId}', 'UsersController@getCard')->name('users.get-card');
        Route::patch('users/{user}/cards/{cardId}', 'UsersController@updateCard')->name('users.update-card');
        Route::post('users/{user}/cards', 'UsersController@addCard')->name('users.add-card');

        # Invoices
        Route::get('invoices', 'InvoicesController@getAll')->name('invoices.get-all');
        Route::get('invoices/{invoice}', 'InvoicesController@getOne')->name('invoices.get-one');
        Route::get('invoices/{invoice}/invoice_items', 'InvoicesController@getItems')->name('invoices.get-items');

        # Invoice Items
        Route::get('invoice_items/{invoice_item}', 'InvoiceItemsController@getOne')->name('invoices.get-one');


        # Patients
        Route::get('patients', 'PatientsController@getAll')->name('patients.get-all');
        Route::get('patients/{patient}', 'PatientsController@getOne')->name('patients.get-one');
        Route::patch('patients/{patient}', 'PatientsController@update')->name('patients.update');
        Route::get('patients/{patient}/invoices', 'PatientsController@getInvoices')->name('patients.get-invoices');

        # Attachments
        Route::get('attachments/{attachment}', 'AttachmentsController@getOne')->name('attachment.get-one');
        Route::post('patients/{patient}/attachments', 'AttachmentsController@store')->name('attachment.store');
        Route::patch('attachments/{attachment}', 'AttachmentsController@update')->name('attachment.update');
        Route::delete('attachments/{attachment}', 'AttachmentsController@delete')->name('attachment.delete');

        # Prescriptions
        Route::get('prescriptions/{prescription}', 'PrescriptionsController@getOne')->name('prescriptions.get-one');
        Route::post('patients/{patient}/prescriptions', 'PrescriptionsController@store')->name('prescriptions.store');
        Route::patch('prescriptions/{prescription}', 'PrescriptionsController@update')->name('prescriptions.update');
        Route::delete('prescriptions/{prescription}', 'PrescriptionsController@delete')->name('prescriptions.delete');

        # SOAP Notes
        Route::get('soap_notes', 'SoapNotesController@getAll')->name('soap_notes.get-all');
        Route::get('soap_notes/{soap_note}', 'SoapNotesController@getOne')->name('soap_notes.get-one');
        Route::patch('soap_notes/{soap_note}', 'SoapNotesController@update')->name('soap_notes.update');
        Route::post('patients/{patient}/soap_notes', 'SoapNotesController@store')->name('soap_notes.store');
        Route::delete('soap_notes/{soap_note}', 'SoapNotesController@delete')->name('soap_notes.delete');

        # Intakes
        Route::get('intakes', 'IntakesController@getAll')->name('intakes.get-all');
        Route::get('intakes/{token}', 'IntakesController@getOne')->name('intakes.get-one');

        # Appointments
        Route::get('appointments', 'AppointmentsController@getAll')->name('appointments.get-all');
        Route::get('appointments/{appointment}', 'AppointmentsController@getOne')->name('appointments.get-one');
        Route::post('appointments', 'AppointmentsController@store')->name('appointments.store');
        Route::patch('appointments/{appointment}', 'AppointmentsController@update')->name('appointments.update');
        Route::delete('appointments/{appointment}', 'AppointmentsController@delete')->name('appointments.delete');

        # Practitioners
        Route::get('practitioners', 'PractitionersController@getAll')->name('practitioner.get-all');
        Route::get('practitioners/{practitioner}', 'PractitionersController@getOne')->name('practitioner.get-one');
        Route::patch('practitioners/{practitioner}', 'PractitionersController@update')->name('practitioner.update');
        Route::post('practitioners/{practitioner}/profile-image', 'PractitionersController@profileImageUpload')->name('practitioners.profile-image-upload');
        Route::post('practitioners/{practitioner}/bg-image', 'PractitionersController@backgroundImageUpload')->name('practitioners.bg-image-upload');
        Route::get('practitioners/{practitioner}/schedule', 'PractitionerScheduleController@show')->name('practitioner-schedule.show');
        Route::patch('practitioners/{practitioner}/schedule', 'PractitionerScheduleController@update')->name('practitioner-schedule.update');

        # Messages
        Route::get('messages', 'MessagesController@getAll')->name('messages.get-all');
        Route::get('messages/{message}', 'MessagesController@getOne')->name('messages.get-one');
        Route::post('messages', 'MessagesController@new')->name('messages.new');
        Route::put('messages/{message}/read', 'MessagesController@read')->name('messages.read');
        Route::delete('messages/{message}', 'MessagesController@delete')->name('messages.delete');

        # Lab Tests
        Route::get('lab/tests/information', 'LabTestsController@getInformation')->name('lab-tests.get-information');
        Route::get('lab/tests', 'LabTestsController@getAll')->name('lab-tests.get-all');
        Route::get('lab/tests/{lab_test}', 'LabTestsController@getOne')->name('lab-tests.get-one');
        Route::post('lab/tests', 'LabTestsController@store')->name('lab-tests.store');
        Route::patch('lab/tests/{lab_test}', 'LabTestsController@update')->name('lab-tests.update');
        Route::delete('lab/tests/{lab_test}', 'LabTestsController@delete')->name('lab-tests.delete');
        Route::get('lab/tests/{lab_test}/track', 'LabTestsController@track')->name('lab-tests.track');

        # Lab Test Results
        Route::get('lab/tests/results/{lab_test_result}', 'LabTestsController@getOneResult')->name('lab-tests.get-one-result');
        Route::post('lab/tests/{lab_test}/results', 'LabTestsController@storeResult')->name('lab-tests.store-result');
        Route::patch('lab/tests/results/{lab_test_result}', 'LabTestsController@updateResult')->name('lab-tests.update-result');
        Route::delete('lab/tests/results/{lab_test_result}', 'LabTestsController@deleteResult')->name('lab-tests.delete-result');

        # Lab Orders
        Route::get('lab/orders', 'LabOrdersController@getAll')->name('lab-orders.get-all');
        Route::get('lab/orders/{lab_order}', 'LabOrdersController@getOne')->name('lab-orders.get-one');
        Route::post('lab/orders', 'LabOrdersController@store')->name('lab-orders.store');
        Route::patch('lab/orders/{lab_order}', 'LabOrdersController@update')->name('lab-orders.update');
        Route::delete('lab/orders/{lab_order}', 'LabOrdersController@delete')->name('lab-orders.delete');
        Route::get('lab/orders/{lab_order}/track', 'LabOrdersController@track')->name('lab-orders.track');
        Route::put('lab/orders/{lab_order}/ship', 'LabOrdersController@ship')->name('lab-orders.ship');

        # SKUs
        Route::get('skus', 'SkusController@index')->name('skus.index');
        Route::get('skus/lab-tests', 'SkusController@indexLabTests')->name('skus.indexLabTests');
        Route::get('skus/{sku}', 'SkusController@show')->name('skus.show');
        Route::post('skus', 'SkusController@store')->name('skus.store');
        Route::put('skus/{sku}', 'SkusController@update')->name('skus.update');
        Route::delete('skus/{sku}', 'SkusController@delete')->name('skus.delete');
        Route::patch('skus/{sku}', 'SkusController@updateListOrder')->name('skus.updateListOrder');
    });
});
