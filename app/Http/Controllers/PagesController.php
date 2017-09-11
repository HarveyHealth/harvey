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

    public function getLabTests($test_slug = null)
    {
      $valid_routes = [];
      $labId = 0;
      $availableTests = LabTestInformation::allFromCache();

      // get available slugs from each test
      foreach ($availableTests as $test) {
        array_push($valid_routes, $test->sku->slug);
      }

      if ($test_slug != null && !in_array($test_slug, $valid_routes)) {
          return view('errors.404');
      }

      $data = array(
        'lab_tests' => $availableTests,
        'lab_test_slug' => $test_slug,
        'id' => $labId
      );

      return view('legacy.pages.lab_tests')->with($data);
    }
}
