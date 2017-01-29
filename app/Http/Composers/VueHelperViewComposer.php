<?php

namespace App\Http\Composers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class VueHelperViewComposer
{
    /*
     * This passes data to *all* views so that
     * it can be used by Vue
     */

    public function compose(View $view)
    {
        $user = Auth::user();

        $user_data = [
            'signedIn' => false,
        ];

        if ($user) {
            $user_data['id'] = $user->id;
            $user_data['signedIn'] = true;
            $user_data['firstName'] = $user->first_name;
            $user_data['lastName'] = $user->lastName;
            $user_data['fullName'] = $user->fullName();
            $user_data['apiToken'] = $user->api_token;
            $user_data['userType'] = $user->user_type;
        }
        ksort($user_data);


        $vue_data = [
            'csrfToken' => csrf_token(),
            'appVersionId' => app()->version_id,
            'stripeKey' => \Config::get('services.stripe.key'),
            'user' => $user_data,
        ];
        ksort($vue_data);

        $view->with('vue_data', json_encode($vue_data));
    }
}
