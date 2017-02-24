<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class EmailVerificationController extends Controller
{
    public function handle($user_id, $token)
    {
        $user = User::findOrFail($user_id);

        if($user->emailVerificationTokenMismatch($token)) {
            abort(404);
        }

        if (!$user->email_verified_at) {
            $user->email_verified_at = Carbon::now();
            $user->save();
        }

        $password_set = isset($user->password);
        $user_type = $user->user_type;

        return view('auth.email_verification')->with(compact('password_set', 'user_type'));
    }
}
