<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\UserRepository;

class PatientsController extends Controller
{
    protected $users;

    function __construct(UserRepository $users)
    {
        $this->middleware('auth');
        $this->middleware('role:practitioner|admin');
        $this->middleware('role:practitioner', ['only' => 'postOrderTest']);

        $this->users = $users;
    }

    public function getIndex()
    {
        $users = $this->users->where('user_type', 'patient')->orderBy('last_name')->get();
        return view('patients.index')->with('users',$users);
    }

    public function getDetails($user_id)
    {
        $user = $this->users->findOrFail($user_id);
        return view('patients.details')->with('user', $user);
    }

    public function postOrderTest($user_id)
    {
        $patient = $this->users->findOrFail($user_id);
        $practitioner = auth()->user();

        $sku = SKU::findOrFail(1);
        $test = Test::create([
                'sku_id' => $sku->id,
                'patient_user_id' => $patient->id,
                'practitioner_user_id' => $practitioner->id,
            ]);

        app()->slack->notify(new TestOrdered($test));
    }
}
