<?php

namespace App\Repositories;

use App\Models\Test;

class TestRepository extends BaseRepository
{
    public $model;

    public function __construct(Test $model)
    {
        $this->model = $model;
    }
}
