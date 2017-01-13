<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InviteController extends Controller
{
    function __construct()
    {
        $this->middleware('role:admin');
    }

    public function getInvite()
    {
        return view('invite.invite');
    }

    public function postInvite(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'user_type' => 'required',
        ]);

        // create the user record
        $user = App\Models\User::create([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'user_type' => $request->input('user_type')
        ]);

        $user->notify(new NewInvitation);
    }
}
