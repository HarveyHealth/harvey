<?php

namespace App\Http\Composers;

use App\Transformers\V1\UserTransformer;
use App\Models\License;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use League\Fractal\Serializer\JsonApiSerializer;
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
            'csrfToken' => csrf_token(),
            'licenseTypes' => License::all()->pluck('title')->unique(),
        ];

        return $data;
    }

    protected function userData()
    {
        $user = Auth::user();

        if (empty($user)) {
            return ['signedIn' => false];
        }

        $fractal = fractal()->item($user)
            ->transformWith(new UserTransformer)
            ->serializeWith(new JsonApiSerializer)
            ->toArray();

        $output = ['signedIn' => true];
        $output += ['id' => $fractal['data']['id']];
        $output += $fractal['data']['attributes'];
    
        if($user->isPractitioner()) {
            $output += ['practitionerId' => $user->practitioner->id];
        }

        return $output;
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
