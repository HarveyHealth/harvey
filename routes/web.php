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
Route::get('lab-tests', 'PagesController@getLabTests');

Route::get('dashboard', 'DashboardController@index')->name('dashboard');
Route::get('api/dashboard', 'DashboardController@index');
Route::get('api/user', 'DashboardController@getUser');

// INVITE USERS
Route::get('invite', 'InviteController@getInvite');
Route::post('invite', 'InviteController@postInvite');

// TERMS
Route::get('terms', 'LegalController@terms');
Route::get('privacy', 'LegalController@privacy');

// SITEMAP
Route::get('sitemap.xml', 'SitemapController@index');
Route::get('sitemap-{map?}.xml', 'SitemapController@index');

if (App::environment('local')) {
    Route::get('test', 'TestController@index');
}
