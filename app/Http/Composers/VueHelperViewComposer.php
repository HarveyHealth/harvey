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
        $data = [
            'app' => $this->appData(),
            'user' => $this->userData(),
            'services' => $this->servicesData(),
        ];

        $view->with('vue_data', json_encode($data));
    }

    protected function appData()
    {
        $data = [
            'csrfToken' => csrf_token(),
            'versionId' => app()->version_id,
        ];
        ksort($data);

        return $data;
    }

    protected function userData()
    {
        $user = Auth::user();

        $data = [
            'signedIn' => false,
        ];

        if ($user) {
            $data['id'] = $user->id;
            $data['signedIn'] = true;
            $data['firstName'] = $user->first_name;
            $data['lastName'] = $user->lastName;
            $data['fullName'] = $user->fullName();
            $data['apiToken'] = $user->api_token;
            $data['userType'] = $user->user_type;
        }
        ksort($data);

        return $data;
    }

    protected function servicesData()
    {
        $data = [
            'stripe' => [
                'key' => \Config::get('services.stripe.key'),
            ]
        ];
        ksort($data);

        return $data;
    }
}
