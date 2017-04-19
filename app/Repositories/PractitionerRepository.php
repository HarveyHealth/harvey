<?php

namespace App\Repositories;

use App\Models\Practitioner;

class PractitionerRepository extends BaseRepository
{
    public $model;

    public function __construct(Practitioner $model)
    {
        $this->model = $model;
    }
}
