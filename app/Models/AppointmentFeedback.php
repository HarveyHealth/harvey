<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\{Builder, Model, SoftDeletes};

class AppointmentFeedback extends Model
{
    protected $table = 'appointment_feedback';

    public function appointment()
    {
        return $this->hasOne(Appointment::class);
    }
}
