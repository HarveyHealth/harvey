<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PractitionerScheduleOverride extends Model
{
    protected $fillable = ['practitioner_id', 'date', 'start_time', 'stop_time', 'notes'];

    public function practitioner()
    {
        return $this->belongsTo(Practitioner::class);
    }
}
