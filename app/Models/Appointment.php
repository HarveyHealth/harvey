<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Traits\HasPatientAndPractitioner;

use App\Lib\Slack;
use App\Notifications\SlackNotification;

class Appointment extends Model
{
    use HasPatientAndPractitioner, SoftDeletes;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'appointment_at'
    ];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    static public function boot()
    {
        parent::boot();

        self::created(function ($appointment) {
            $patient = $appointment->patient;
            $practitioner = $appointment->practitioner;
            $time = new \Carbon($appointment->appointment_at);
            $time->timezone = 'America/Los_Angeles';

            $message = '*[New Appointment]* ' . $patient->fullName() . ' with ' . $practitioner->fullName() . ' on ' . $time->format('M j') . ' at ' . $time->format('g:ia');

            (new Slack)->notify(new SlackNotification($message, 'business'));
        });
    }

    public function notes()
    {
        return $this->hasMany(PatientNote::class);
    }

    /*
     * SCOPES
     */
    public function scopeUpcoming($query)
    {
        return $query->where('appointment_at', '>', \Carbon::now())->orderBy('appointment_at', 'ASC');
    }

    public function scopeRecent($query, $limit = 3)
    {
        return $query->where('appointment_at', '<', \Carbon::now())->limit($limit)->orderBy('appointment_at', 'DESC');
    }
}
