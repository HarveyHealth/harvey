<?php

namespace App\Http\Controllers\Auth;

use App\Models\{Appointment, Patient, User};
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Events\{UserRegistered};
use Exception, ResponseCode;
use Auth;
use Socialite;
use Redis;
use Session;

class AuthController extends Controller
{
    // Redirect the user to the OAuth Provider.
    public function redirectToProvider($provider, Request $request)
    {
        return Socialite::driver($provider)->redirect();
    }

    // Obtain the user information from provider.  Check if the user already exists in our
    // database by looking up their provider_id in the database.
    // If the user exists, log them in. Otherwise, create a new user then log them in.
    public function handleProviderCallback($provider)
    {
        // Grab facebook user information
        $user = Socialite::driver($provider)->user();
        // Determine if user currently exists from previous facebook signin
        $existingUser = User::where('provider_id', $user->id)->first();

        // login and redirect based on appointment history
        if ($existingUser) {
          $patientId = Patient::where('user_id', $existingUser->id)->first()->id;
          $hasAppointment = Appointment::where('patient_id', $patientId)->first();
          $toPage = $hasAppointment ? '/dashboard' : '/get-started';
          Auth::loginUsingId($existingUser->id, true);

          return redirect($toPage);
        } else {
          // Get zip code stored in Redis
          $sessionId = Session::getId();
          $zip = Redis::get("login-zip-{$sessionId}");
          Redis::del("login-zip-{$sessionId}");

          // Create user
          User::unguard();
          $_user = new User([
              'first_name' => explode(' ', $user->name)[0],
              'last_name' => explode(' ', $user->name)[1],
              'email' => $user->email,
              'image_url' => $user->avatar,
              'terms_accepted_at' => \Carbon::now(),
              'provider' => $provider,
              'provider_id' => $user->id,
              'zip' => $zip,
          ]);
          $_user->save();
          $_user->patient()->save(new Patient());
          // Emit the user registration event for email send
          // event(new UserRegistered($_user));
          Auth::login($_user, true);
          // return $zip;
          return redirect('/get-started#/welcome');
        }
    }
  }
