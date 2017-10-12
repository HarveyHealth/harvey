<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\Contact;
use App\Models\{
  Condition,
  LabTestInformation
};

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

    public function getLabTests(string $labTestSlug = null)
    {
        $lab_tests = LabTestInformation::publicFromCache();

        $index = $lab_tests->pluck('sku')->search(function ($item) use ($labTestSlug) {
            return $item->slug == $labTestSlug;
        });

        if (false === $index) {
            return redirect(route('lab-tests', $lab_tests->first()->sku->slug));
        }

        $sku_id = $lab_tests->pluck('sku')->get($index)->id;

        return view('legacy.pages.lab_tests')->with(compact('lab_tests', 'sku_id'));
    }

    public function getConditions(string $conditionSlug = null)
    {
        $conditions = Condition::all();

        $index = $conditions->search(function ($item) use ($conditionSlug) {
            return $item->slug === $conditionSlug;
        });

        if ($index === false && $conditionSlug !== null) {
            return redirect()->route('conditions');
        }

        if ($conditionSlug === null) {
          $index = 'null';
        }

        return view('pages.conditions')->with(compact('conditions', 'index'));
    }
}
