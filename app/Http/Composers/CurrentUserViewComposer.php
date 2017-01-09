<?php

namespace App\Http\Composers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class CurrentUserViewComposer {

    public function compose(View $view) {
        $view->with('current_user', Auth::user());
        $view->with('signed_in', Auth::check());
    }
}
