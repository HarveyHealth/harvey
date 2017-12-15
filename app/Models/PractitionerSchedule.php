<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PractitionerSchedule extends Model
{
    protected $fillable = ['practitioner_id', 'day_of_week','start_time','stop_time', 'notes'];

    public function practitioner()
    {
        return $this->belongsTo(Practitioner::class);
    }
}
