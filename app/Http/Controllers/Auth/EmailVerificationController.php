<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class EmailVerificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function handle($user_id, $token) {

        $user = User::findOrFail($user_id);

        if ($token != bcrypt($user->id . '|' . $user->email . '|' . $user->created_at))
            abort(404);

        auth()->login($user);

        return view('auth.email_verified');
    }
}
