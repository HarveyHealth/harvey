<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;

class UsersController extends Controller
{
    private $users;

    /**
     * UsersController constructor.
     * @param UserRepository $users
     */
    public function __construct(UserRepository $users)
    {
        $this->users = $users;
        $this->middleware('auth', ['except' => 'getProfile']);
    }

    public function getList()
    {
        $users = $this->users->all();
        return view('users.list', ['users' => $users]);
    }

    public function getProfile($id)
    {
        $user = $this->users->findOrFail($id);
        return view('users.profile', ['user' => $user]);
    }

    public function getAccount($id = null)
    {
        if (empty($id)) {
            $id = request()->user();
        }
    }

    public function postAccount($id = null)
    {
        if (empty($id)) {
            $id = request()->user();
        }
    }
    
    public function update(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|max:10',
            'gender' => 'required',
            'birthdate' => 'required',
            'height' => 'required',
            'weight' => 'required'
        ]);
    
        $user = auth()->user();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->gender = $request->gender;
        $user->birthdate = Carbon::parse($request->birthdate);
        $user->height = $request->height;
        $user->weight = $request->weight;
        $user->save();
        
        $response = [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'phone' => $user->phone,
            'gender' => $user->gender,
            'birthdate' => $user->birthdate,
            'height' => $user->height,
            'weight' => $user->weight
        ];
    
        return response()->json($response);
    }
}
