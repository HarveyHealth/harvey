<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserRegistered;
use App\Http\Controllers\Controller;
use App\Lib\ZipCodeValidator;
use App\Models\{Appointment, Patient, User};
use Illuminate\Http\Request;
use Auth, Carbon, Socialite;

class AuthController extends Controller
{
    public function __construct(ZipCodeValidator $zip_code_validator)
    {
        $this->zip_code_validator = $zip_code_validator;
    }

    private function determineView($user) {
        return $user->appointments()->first() ? '/dashboard' : '/get-started';
    }

    // Redirect the user to the OAuth Provider.
    public function redirectToFacebookProvider(Request $request)
    {
        if (is_numeric($request->zip)) {
            session(['zip' => $request->zip]);
        } else {
            session(['no_zip' => true]);
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
        if ($existing_user = User::where('facebook_provider_id', $user->id)->first()) {

            Auth::loginUsingId($existing_user->id, true);
            return redirect($this->determineView($existing_user));

        } else {

            // Determine if the email is already used in the db. If so, update facebook_provider_id and login
            if ($current_legacy_user = User::where('email', $user->email)->first()) {
              $current_legacy_user->update(['facebook_provider_id' => $user->id]);
              Auth::loginUsingId($current_legacy_user->id, true);
              return redirect($this->determineView($current_legacy_user));
            }

            if (session('no_zip')) {
                session(['no_zip' => false]);
                return redirect('/conditions');
            }
            // Get zip, city, state
            $zip = session('zip');
            $this->zip_code_validator->setZip($zip);
            $city = $this->zip_code_validator->getCity();
            $state = $this->zip_code_validator->getState();

            // parse facebook name to at least get the last name correctly
            $full_name = explode(' ', trim($user->name));
            $last_name = array_pop($full_name);
            $first_name = implode(' ', $full_name);

            // Create user and patient
            $_user = User::create([
                'first_name' => $first_name,
                'last_name' => $last_name,
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
