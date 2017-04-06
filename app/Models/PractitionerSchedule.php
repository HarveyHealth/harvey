<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PractitionerSchedule extends Model
{
    public function practitioner()
    {
        return $this->belongsTo(Practitioner::class);
    }
}
