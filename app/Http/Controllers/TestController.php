<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;

class TestController extends Controller
{
    public function index()
    {
    	$customer_id = 'cus_A4qUQuiPXt1vWV';

    	$amount = 15000;

    	$data = [
            		'customer' => $customer_id,
            		'amount' => $amount,
            		'currency' => 'usd',
            		'description' => 'Because I wanted to, man'
            	];

        try {

        	$charge = \Stripe\Charge::create($data);
        	print_r($charge);

        	if ($charge->paid) {

        		echo $charge->source->last4;

        	} else {

        	}
        	
        } catch (\Exception $e) {

        	print_r($e->getMessage());
        	exit;
        }
        
	}
}
