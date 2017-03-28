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

    public function getContact()
    {
        return view('pages.contact');
    }

    public function getLabTests()
    {
        return view('pages.lab_tests');
    }

    public function postContact(Request $request)
    {
        $this->validate($request, [
            'email' => 'email|required',
            'message' => 'required'
        ]);

        Mail::to('support@homehero.org')->send(new Contact());
    }

    // Showing the static signup pages
    public function getSignup()
    {
        return view('static.signup');
    }
}
