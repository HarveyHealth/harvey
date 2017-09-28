<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserRegistered;
use App\Models\{Appointment, Patient, User};
use App\Lib\zipCodeValidator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth, Socialite;

class AuthController extends Controller
{

    public function __construct(ZipCodeValidator $zipCodeValidator)
    {
        $this->zipCodeValidator = $zipCodeValidator;
    }

    // Redirect the user to the OAuth Provider.
    public function redirectToFacebookProvider(Request $request)
    {
        if (!$request->zip) {
            session(['no_zip' => true]);
        } else {
            session(['zip' => $request->zip]);
        }
        return Socialite::driver('facebook')->redirect();
    }

    // Obtain the user information from provider.  Check if the user already exists in our
    // database by looking up their provider_id in the database.
    // If the user exists, log them in. Otherwise, create a new user then log them in.
    public function handleFacebookProviderCallback()
    {
        // Grab facebook user information
        $user = Socialite::driver('facebook')->user();

        // Determine if user currently exists from previous facebook signin
        if ($existingUser = User::where('facebook_provider_id', $user->id)->first()) {
          // login and redirect based on appointment history
          $patientId = $existingUser->patient->id;
          $hasAppointment = $existingUser->appointments()->first();
          $toPage = $hasAppointment ? '/dashboard' : '/get-started';
          Auth::loginUsingId($existingUser->id, true);
          return redirect($toPage);

        } else {
          if (session('no_zip')) {
            session(['no_zip' => false]);
            return redirect('/conditions');
          }
          // Get zip, city, state
          $zip = session('zip');
          $this->zipCodeValidator->setZip($zip);
          $city = $this->zipCodeValidator->getCity();
          $state = $this->zipCodeValidator->getState();

          // Create user and patient
          $_user = User::create([
              'first_name' => explode(' ', $user->name)[0],
              'last_name' => explode(' ', $user->name)[1],
              'email' => $user->email,
              'image_url' => $user->avatar,
              'terms_accepted_at' => \Carbon::now(),
              'facebook_provider_id' => $user->id,
              'zip' => $zip,
              'city' => $city,
              'state' => $state,
          ]);

          $_user->patient()->save(new Patient());

          event(new UserRegistered($_user));

          Auth::login($_user, true);

          return redirect('/get-started#/welcome');
        }
    }
  }
