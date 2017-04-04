<?php

// Configure the routes for Pitbull
Route::group(['prefix' => 'pitbull', 'middleware' => ['web','auth']], function () {
    Route::get('', '\August\Pitbull\Http\Controllers\PitbullController@getUsers');
    Route::get('/user/{id}', '\August\Pitbull\Http\Controllers\PitbullController@getUser');

    Route::get('roles', '\August\Pitbull\Http\Controllers\PitbullController@getRoles');
    Route::post('roles', '\August\Pitbull\Http\Controllers\PitbullController@postRoles');

    // Ajax routes
    Route::get('rolesforuser/{id}', '\August\Pitbull\Http\Controllers\PitbullController@getRolesForUser');
    Route::get('permissionsforuser/{id}', '\August\Pitbull\Http\Controllers\PitbullController@getPermissionsForUser');
    Route::get('searchroles', '\August\Pitbull\Http\Controllers\PitbullController@getSearchRoles');
    Route::get('searchpermissions/{id}', '\August\Pitbull\Http\Controllers\PitbullController@getSearchPermissions');
});
