<?php

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
Route::get('verify/{id}/{token}', 'Auth\EmailVerificationController@handle');


// basic public pages
Route::get('/', 'PagesController@getHomepage');
Route::get('about', 'PagesController@getAbout');
Route::get('contact', 'PagesController@getContact');
Route::post('contact', 'PagesController@postContact');


Route::get('dashboard', 'DashboardController@index');

// USERS
Route::get('account/{id?}','UsersController@getAccount');
Route::post('account/{id?}','UsersController@postAccount');
Route::get('users/list','UsersController@getList');
Route::get('users/{id}','UsersController@getProfile');



// SITEMAP
Route::get('sitemap.xml','SitemapController@index');
Route::get('sitemap-{map?}.xml','SitemapController@index');
