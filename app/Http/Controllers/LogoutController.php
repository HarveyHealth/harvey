<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
	public function index()
	{
		try {
			if (Auth::check()) {
				Auth::logout();
			}
		} catch(Exception $e) {
			Log::info('Error when attempting to log out.', ['request' => Request::all()]);
		}

		return redirect()->route('home');
	}
}
