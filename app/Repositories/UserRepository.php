<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends BaseRepository
{
    public $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }
    
    public function getByApiToken($token)
    {
        return $this->model->whereApiToken($token)->first();
    }
}
