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

        if (!is_numeric($index)) {
            return redirect(route('lab-tests', $lab_tests->first()->sku->slug));
        }

        return view('legacy.pages.lab_tests')->with(compact('lab_tests', 'index'));
    }

    public function getConditions()
    {
        $conditions = Condition::all();
        $index = false;

        return view('pages.conditions')->with(compact('conditions', 'index'));
    }

    public function getFinancing()
    {
        return view('legacy.pages.financing');
    }

    public function getConditions(string $conditionSlug = null)
    {
        $conditions = Condition::all();

        $index = $conditions->search(function ($item) use ($conditionSlug) {
            return $item->slug == $conditionSlug;
        });

        if (!is_numeric($index)) {
            return redirect()->route('conditions');
        }

        return view('pages.conditions')->with(compact('conditions', 'index'));
    }
}
