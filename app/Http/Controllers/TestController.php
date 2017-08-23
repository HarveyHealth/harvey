<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index()
    {
    	$order = \App\Models\LabOrder::findOrFail(1);
        print_r($order->dataForInvoice());

        $appointment = \App\Models\Appointment::findOrFail(6);
        print_r($appointment->dataForInvoice());
    }
}
