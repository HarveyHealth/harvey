<?php

namespace App\Http\Composers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Config;

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
            'services' => $this->servicesData(),
            'user' => $this->userData(),
        ];

        $view->with('vue_data', json_encode($data));
    }

    protected function appData()
    {
        $data = [
            'csrfToken' => csrf_token()
        ];

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
            $data['lastName'] = $user->last_name;
            $data['fullName'] = $user->fullName();
            $data['userType'] = $user->type;
            if($user->isPractitioner()) {
                $data['practitionerId'] = $user->practitioner->id;
            }
        }

        return $data;
    }

    protected function servicesData()
    {
        $data = [
            'stripe' => [
                'key' => Config::get('services.stripe.key'),
            ],
            'pusher' => [
                'key' => Config::get('broadcasting.connections.pusher.key')
            ],
        ];

        return $data;
    }
}
