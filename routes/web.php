<?php
use Illuminate\Routing\Controller;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "Web" middleware group. Build something great!
|
*/

// AUTHENTICATION
Auth::routes();

// Additional routing to not use Laravel's built-in "register" route
Route::get('signup', 'Auth\RegisterController@showRegistrationForm')->name('signup');
Route::post('signup', 'Auth\RegisterController@register');
Route::get('verify/{user_id}/{token}', 'Auth\EmailVerificationController@verify')->middleware('guest');
Route::post('verify/{user_id}/{token}', 'Auth\EmailVerificationController@setPassword')->middleware('guest');

// basic public pages
Route::get('/', 'PagesController@getHomepage')->name('home');
Route::get('about', 'PagesController@getAbout');
Route::get('contact', 'PagesController@getContact');
Route::get('lab-tests', 'PagesController@getLabTests');
Route::post('contact', 'PagesController@postContact');
Route::get('test', 'PagesController@getTest');

Route::get('dashboard', 'DashboardController@index')->name('dashboard');
Route::get('api/dashboard', 'DashboardController@index');
Route::get('api/user', 'DashboardController@getUser');

// USERS
Route::get('account/{id?}', 'UsersController@getAccount');
Route::post('account/{id?}', 'UsersController@postAccount');
Route::get('users/list', 'UsersController@getList');
Route::get('users/{id}', 'UsersController@getProfile');
Route::put('api/users', 'UsersController@update');

// APPOINTMENTS
Route::post('api/appointments', 'AppointmentsController@store');

// INVITE USERS
Route::get('invite', 'InviteController@getInvite');
Route::post('invite', 'InviteController@postInvite');

// UPLOAD Controller
Route::get('upload/test/{id}', 'UploadController@getUploadTest');
Route::post('upload/test/{id}', 'UploadController@postUploadTest');

// TERMS
Route::get('terms', 'LegalController@terms');
Route::get('privacy', 'LegalController@privacy');

// SITEMAP
Route::get('sitemap.xml', 'SitemapController@index');
Route::get('sitemap-{map?}.xml', 'SitemapController@index');

// STATIC SIGNUP PAGES
Route::get('static/signup', 'PagesController@getSignup');
Route::get('static/signup2', 'PagesController@getSignup2');
