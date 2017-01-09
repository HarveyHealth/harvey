<?php

namespace August\Pitbull;


use August\Pitbull\Models\PBUser;

class PitbullController extends \App\Http\Controllers\Controller
{
    function __construct()
    {
        $this->middleware(config('pitbull.middleware'));
    }

    public function getUsers()
    {
        $users = PBUser::all();
        return view('pitbull::user_list')->with('users',$users)->with('fields', config('pitbull.user_fields'));
    }

    public function getUser($user_id)
    {
        $user = PBUser::findOrFail($user_id);
        return view('pitbull::user_details')->with('user', $user)->with('fields', config('pitbull.user_fields'));;
    }


    public function getRoles()
    {

    }

    public function postRoles()
    {

    }

    public function getPermissions()
    {

    }

    public function postPermissions()
    {

    }

    public function getRolesForUser($user_id)
    {
        return ['red', 'blue', 'yellow'];
    }

    public function getPermissionsForUser($user_id)
    {
        return ['green', 'purple', 'orange'];
    }

    public function getSearchRoles()
    {
        $query = request('query');
    }

    public function getSearchPermissions()
    {
        $query = request('query');

    }
}
