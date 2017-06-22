<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GettingStartedController extends Controller
{
    public function index()
    {
        if (auth()->check()) {
            return redirect(route('dashboard'));
        }

        return view('pages.gettingstarted');
    }
}
