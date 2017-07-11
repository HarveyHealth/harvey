<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
class IntakeController extends Controller
{
  // public function __construct()
  // {
  //     $this->middleware('auth');
  // }
  public function index()
  {
    if (auth()->check()) {
      return redirect(route('dashboard'));
    }
    return view('pages.intake');
  }
}
