<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

class ResponseMacroServiceProvider extends ServiceProvider
{
    /**
     * Register the application's response macros
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('apiproblem',  function($apiproblem, $status = 404, $headers = []) {
            if (!is_array($apiproblem)) {
                $apiproblem = [$apiproblem];
            }
            
            return response()->json([
                'errors' => [$apiproblem]
            ], $status);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
