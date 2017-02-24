<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class EmailVerificationController extends Controller
{
    public function verify($user_id, $token)
    {
        $user = User::findOrFail($user_id);

        if($user->emailVerificationTokenMismatch($token)) {
            abort(404);
        }

        if (!$user->email_verified_at) {
            $user->email_verified_at = Carbon::now();
            $user->save();
        }

        $password_not_set = $user->passwordNotSet();
        $user_type = $user->user_type;

        return view('auth.email_verification')
            ->with(compact('password_not_set', 'user_type', 'user_id', 'token'));
    }

    public function setPassword(Request $request, $user_id, $token)
    {
        $user = User::findOrFail($user_id);

        if($user->emailVerificationTokenMismatch($token)) {
            abort(404);
        }

        $user->password = bcrypt($request->get('password'));
        $user->save();
        auth()->login($user);

        return redirect()->route('dashboard');
    }
}
