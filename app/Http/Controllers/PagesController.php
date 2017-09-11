<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\Contact;
use App\Models\LabTestInformation;

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
        return view('legacy.pages.homepage');
    }

    public function getAbout()
    {
        return view('legacy.pages.about')->with(['about']);
    }

    public function getLabTests()
    {
      $valid_routes = ['tab-1', 'tab-2', 'tab-3'];

      if($test_slug != null && !in_array($test_slug, $valid_routes)) {
          return view('errors.404');
      }

      $data = array(
        'lab_tests' => LabTestInformation::allFromCache(),
        'lab_test_slug' => $test_slug,
      );

      return view('legacy.pages.lab_tests')->with($data);
    }
}
