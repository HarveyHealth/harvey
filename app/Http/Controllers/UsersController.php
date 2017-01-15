<?php

namespace App\Http\Controllers;

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
}
