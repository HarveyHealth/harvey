<?php
use Illuminate\Routing\Controller;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// AUTHENTICATION
Auth::routes();

// Additional routing to not use Laravel's built-in "register" route
Route::get('signup', 'Auth\RegisterController@showRegistrationForm')->name('signup');
Route::post('signup', 'Auth\RegisterController@register');
Route::get('verify/{id}/{token}', 'Auth\EmailVerificationController@getVerify');
Route::post('verify/{id}/{token}', 'Auth\EmailVerificationController@postVerify');
Route::get('logout', 'LogoutController@index')->name('logout');


// basic public pages
Route::get('/', 'PagesController@getHomepage')->name('home');
Route::get('about', 'PagesController@getAbout');
Route::get('contact', 'PagesController@getContact');
Route::post('contact', 'PagesController@postContact');
Route::get('test', 'PagesController@getTest');

Route::get('dashboard', 'DashboardController@index');

// USERS
Route::get('account/{id?}', 'UsersController@getAccount');
Route::post('account/{id?}', 'UsersController@postAccount');
Route::get('users/list', 'UsersController@getList');
Route::get('users/{id}', 'UsersController@getProfile');

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
