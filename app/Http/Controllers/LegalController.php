<?php

namespace App\Http\Controllers;

class LegalController extends Controller
{
    public function terms()
    {
        $html = html_from_markdown_file(resource_path('/views/legacy/legal/markdown/terms.md'));

        return view('legacy.legal.legal')->with('html', $html)->with('page_title', 'Terms and Conditions');
    }

    public function privacy()
    {
        $html = html_from_markdown_file(resource_path('/views/legacy/legal/markdown/privacy.md'));

        return view('legacy.legal.legal')->with('html', $html)->with('page_title', 'Privacy Policy');
    }
}
