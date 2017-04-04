<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\Contact;

/*
 * A light controller to display mostly static pages, like the homepage
 */
class PagesController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the home page.
     *
     * @return \Illuminate\Http\Response
     */
    public function getHomepage()
    {
        return view('pages.homepage');
    }

    public function getAbout()
    {
        return view('pages.about');
    }

    public function getLabTests()
    {
        return view('pages.lab_tests');
    }
    
    // Showing the static signup pages
    public function getSignup()
    {
        return view('static.signup');
    }
}
