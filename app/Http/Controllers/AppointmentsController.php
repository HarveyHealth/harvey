<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppointmentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
}
