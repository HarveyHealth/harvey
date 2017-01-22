<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\HasPatientAndPractitioner;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use HasPatientAndPractitioner, SoftDeletes;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'appointment_at'
    ];

    public function notes()
    {
        return $this->hasMany(PatientNote::class);
    }

    /*
     * SCOPES
     */
    public function scopeUpcoming($query)
    {
        return $query->where('appointment_at', '>', \Carbon::now());
    }

    public function scopeRecent($query, $limit = 3)
    {
        return $query->where('appointment_at', '<', \Carbon::now())->limit($limit);
    }
}
