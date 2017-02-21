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

        if ($token != $user->emailVerificationToken()) {
            abort(404);
        }

        if($user->email_verified_at) {
            $verified_already = true;
        } else {
            $user->email_verified_at = Carbon::now();
            $verified_already = false;
        }

        $user->save();

        return view('auth.email_verification')->with(['verified_already' => $verified_already]);
    }
}
