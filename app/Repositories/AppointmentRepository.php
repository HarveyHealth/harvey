<?php

namespace App\Repositories;

use App\Models\Appointment;
use App\Models\Practitioner;

class AppointmentRepository extends BaseRepository
{
    public $model;

    public function __construct(Appointment $model)
    {
        $this->model = $model;
    }
}
