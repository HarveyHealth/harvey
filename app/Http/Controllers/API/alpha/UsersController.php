<?php

namespace App\Http\Controllers\API\alpha;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\API\alpha\Transformers\UserTransformer;

class UsersController extends BaseAPIController
{
    protected $transformer;
    
    public function __construct(UserTransformer $transformer)
    {
        $this->transformer = $transformer;
    }
    
    public function index(Request $request)
    {
        // List multiple users
    }

    public function store(Request $request)
    {
        // I don't foresee the need to create multiple users at a time.
    }

    public function create(Request $request)
    {
        // This logic is in the RegisterController
    }

    public function show(User $user)
    {
        $transformedUser = $this->transformer->transform($user);
        
        return $this->respond($transformedUser);
    }

    public function update(Request $request, User $user)
    {
        $user->update($request->except('api_token'));
    
        $transformedUser = $this->transformer->transform($user);
    
        return $this->respond($transformedUser);
        
    }
}
