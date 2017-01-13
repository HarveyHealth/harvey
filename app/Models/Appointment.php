<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\HasPatientAndPractitioner;

class Appointment extends Model
{
    use HasPatientAndPractitioner;


    /*
     * SCOPES
     */
    public function scopeUpcoming($query)
    {
     return $query->whereNull('appointment_at', '>', \Carbon::now());
    }

    public function scopeRecent($query, $limit = 3)
    {
        return $query->where('appointment_at', '<', \Carbon::now())->limit($limit);
    }

    public function scopePending($query, $limit = 3)
    {
        return $query->where('appointment_at', '>', \Carbon::now());
    }
}
