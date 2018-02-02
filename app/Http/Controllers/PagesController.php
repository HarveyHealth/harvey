<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\Contact;
use App\Lib\HarveyAvailability;
use App\Models\{Condition, LabTestInformation};

/*
 * A light controller to display mostly static pages, like the homepage
 */
class PagesController extends Controller
{
    /**
     * Show the home page.
     *
     * @return \Illuminate\Http\Response
     */
    public function getHomepage()
    {
        return view('legacy.pages.homepage')->with(['conditions' => Condition::getAllFromCache()]);
    }

    public function getAbout()
    {
        return view('legacy.pages.about')->with(['about']);
    }

    public function getLabTests(string $labTestSlug = null)
    {
        return redirect('https://store.goharvey.com/collections/lab-tests');

        $lab_tests = LabTestInformation::publicFromCache();

        $index = $lab_tests->pluck('sku')->search(function ($item) use ($labTestSlug) {
            return $item->slug == $labTestSlug;
        });

        if (!is_numeric($index)) {
            return redirect(route('lab-tests', $lab_tests->first()->sku->slug));
        }

        return view('legacy.pages.lab_tests')->with(compact('lab_tests', 'index'));
    }

    public function getConsultations()
    {
        return view('legacy.pages.consultations');
    }

    public function getAvailability()
    {
        return view('legacy.pages.availability')->with(['availability' => HarveyAvailability::getAsJson()]);
    }

    public function getRobots()
    {
        $extension = isProd() ? 'prod' : 'generic';
        $robots = file_get_contents(resource_path() . "/robots.{$extension}");

        return response()->make($robots)->header('Content-Type', 'text/plain');
    }

    public function getRobots()
    {
        $extension = isProd() ? 'prod' : 'generic';
        $robots = file_get_contents(resource_path() . "/robots.{$extension}");

        return response()->make($robots)->header('Content-Type', 'text/plain');
    }
}
