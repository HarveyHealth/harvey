<?php

namespace App\Models;

use App\Http\Traits\HasPatientAndPractitioner;
use App\Lib\Slack;
use App\Notifications\SlackNotification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use HasPatientAndPractitioner, SoftDeletes;
    
    /**
     * An appointment will lock when less than 2 hours away.
     */
    const CANCEL_LOCK = 2;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'appointment_at'
    ];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($appointment) {
            if (empty($appointment->appointment_block_ends_at)) {
                $start = new Carbon($appointment->created_at);
                $appointment->appointment_block_ends_at = $start->addMinutes(89)->toDateTimeString();
            }
        });

        self::created(function ($appointment) {
            $patient = $appointment->patient;
            $practitioner = $appointment->practitioner;
            $time = new Carbon($appointment->appointment_at);
            $time->timezone = 'America/Los_Angeles';

            $message = '*[New Appointment]* ' . $patient->user->fullName() . ' with ' . $practitioner->user->fullName() . ' on ' . $time->format('M j') . ' at ' . $time->format('g:ia');

            (new Slack)->notify(new SlackNotification($message, 'business'));
        });
    }

    /*
     * Relationships
     */
    public function notes()
    {
        return $this->hasMany(PatientNote::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function practitioner()
    {
        return $this->belongsTo(Practitioner::class);
    }
    
    public function isLocked()
    {
        $appointment_time = Carbon::parse($this->appointment_at);
        return Carbon::now()->diffInHours(
            $appointment_time,
            true
        ) <= self::CANCEL_LOCK;
    }
    
    public function isNotLocked()
    {
        return !$this->isLocked();
    }

    /*
     * SCOPES
     */
    public function scopeUpcoming($query, $weeks = 2)
    {
        $end_date = (new Carbon())->addWeeks($weeks);

        return $query->where('appointment_at', '>', \Carbon::now())
                    ->where('appointment_at', '<=', $end_date->toDateTimeString())
                    ->orderBy('appointment_at', 'ASC');
    }

    public function scopeRecent($query, $limit = 3)
    {
        return $query->where('appointment_at', '<', \Carbon::now())->limit($limit)->orderBy('appointment_at', 'DESC');
    }

    public function scopeForPractitioner($query, Practitioner $practitioner)
    {
        return $query->where('practitioner_id', '=', $practitioner->id);
    }

    public function scopeForPatient($query, Patient $patient)
    {
        return $query->where('patient_id', '=', $patient->id);
    }

    public function scopeWithinDateRange($query, Carbon $start_date, Carbon $end_date)
    {
        return $query->where('appointment_at', '>=', $start_date->toDateTimeString())
                    ->where('appointment_at', '<=', $end_date->toDateTimeString())
                    ->orderBy('appointment_at', 'ASC');
    }
}
