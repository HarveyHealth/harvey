<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function getUploadTest($test_id)
    {
        // show upload results form
    }

    public function poseUploadTest($test_id)
    {
        // upload to S3 and save the URL to the test object
    }
}
