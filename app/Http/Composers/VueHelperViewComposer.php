<?php

namespace App\Http\Composers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class VueHelperViewComposer
{
    public function compose(View $view)
    {
        $user = Auth::user();

        $vue_data = [
            'appVersionId' => app()->version_id,
            'userId' => ($user ? $user->id : false),
            'csrfToken' => csrf_token(),
            'apiToken' => ($user ? $user->api_token : false),
            'stripeKey' => \Config::get('services.stripe.key'),
        ];

        ksort($vue_data);

        $view->with('vue_data', $vue_data);
    }
}
