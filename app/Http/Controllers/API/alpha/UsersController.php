<?php

namespace App\Http\Controllers\API\alpha;

use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends BaseAPIController
{
    public function index(Request $request)
    {
        echo 'hello';
    }

    public function store(Request $request)
    {
    }

    public function create(Request $request)
    {
    }

    public function show(Request $request, $user_id)
    {
        $user = User::find($user_id);

        if (!$user) {
            return $this->errorNotFound();
        }

        return $this->respondWithItem($user);
    }

    public function update(Request $request, $user_id)
    {
    }
}
