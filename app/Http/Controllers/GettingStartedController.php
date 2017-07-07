<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GettingStartedController extends Controller
{
    public function index()
    {
        return view('pages.gettingstarted');
    }
}
