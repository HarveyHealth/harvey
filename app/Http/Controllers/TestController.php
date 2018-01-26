<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;

class TestController extends Controller
{
    public function index()
    {
        return 'This is a test.';
    }
}
