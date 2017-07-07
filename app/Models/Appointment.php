<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\{Builder, Model, SoftDeletes};
use App\Http\Traits\{BelongsToPatientAndPractitioner, HasStatusColumn};
use App\Lib\TransactionalEmail;
use Lang;
use Log;

class Appointment extends Model
{
    use SoftDeletes, HasStatusColumn, BelongsToPatientAndPractitioner;

    /**
     * An appointment will lock when less than 4 hours away.
     */
    const CANCEL_LOCK = 4;

    const PENDING_STATUS_ID = 0;
    const NO_SHOW_PATIENT_STATUS_ID = 1;
    const NO_SHOW_DOCTOR_STATUS_ID = 2;
    const GENERAL_CONFLICT_STATUS_ID = 3;
    const CANCELED_STATUS_ID = 4;
    const COMPLETE_STATUS_ID = 5;

    const APPOINTMENT_TYPE_ID = 0;
    const FIRST_APPOINTMENT_TYPE_ID = 1;
    const FOLOW_UP_TYPE_ID = 2;

    protected $dates = [
        'appointment_at',
        'created_at',
        'deleted_at',
        'updated_at',
    ];

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at', 'status_id', 'type_id'];

    const STATUSES = [
        self::PENDING_STATUS_ID => 'pending',
        self::NO_SHOW_PATIENT_STATUS_ID => 'no_show_patient',
        self::NO_SHOW_DOCTOR_STATUS_ID => 'no_show_doctor',
        self::GENERAL_CONFLICT_STATUS_ID => 'general_conflict',
        self::CANCELED_STATUS_ID => 'canceled',
        self::COMPLETE_STATUS_ID => 'complete',
    ];

    const TYPES = [
        self::APPOINTMENT_TYPE_ID => 'appointment',
        self::FIRST_APPOINTMENT_TYPE_ID => 'first_appointment',
        self::FOLOW_UP_TYPE_ID => 'follow_up',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('enabledPractitioner', function (Builder $builder) {
            return $builder->whereHas('practitioner.user', function ($query){
                $query->where('enabled', true);
            });
        });

        static::addGlobalScope('enabledPatient', function (Builder $builder) {
            return $builder->whereHas('patient.user', function ($query){
                $query->where('enabled', true);
            });
        });
    }

    /*
     * Relationships
     */
    public function notes()
    {
        return $this->hasMany(PatientNote::class);
    }

    public function reminders()
    {
        return $this->hasMany(AppointmentReminder::class);
    }

    public function getTypeAttribute()
    {
        return empty(self::TYPES[$this->type_id]) ? null : self::TYPES[$this->type_id];
    }

    public function setTypeAttribute($value)
    {
        if (false !== ($key = array_search($value, self::TYPES))) {
            $this->type_id = $key;
        }

        return $value;
    }

    public function getTypeFriendlyName()
    {
        $tableName = $this->getTable();

        return $this->type ? Lang::get("{$tableName}.types.{$this->type}") : null;
    }

    public function isLocked()
    {
        return $this->hoursToStart() <= self::CANCEL_LOCK;
    }

    public function isNotLocked()
    {
        return !$this->isLocked();
    }

    public function isFirst()
    {
        return self::forPatient($this->patient)->complete()->limit(1)->get()->isEmpty();
    }

    public function hoursToStart()
    {
        return Carbon::now()->diffInHours($this->appointment_at, false);
    }

    public function patientAppointmentAtDate()
    {
        return $this->appointment_at->timezone($this->patient->user->timezone);
    }

    public function practitionerAppointmentAtDate()
    {
        return $this->appointment_at->timezone($this->practitioner->user->timezone);
    }

    /*
     * SCOPES
     */
    public function scopeUpcoming(Builder $query, int $weeks = 4)
    {
        return $query->afterThan(Carbon::now())->beforeThan(Carbon::now()->addWeeks($weeks))->byAppointmentAtAsc();
    }

    public function wasPatientReminderEmail24HsSent()
    {
        return (bool) $this->reminders()->email24HsType()->toRecipient($this->patient->user)->count();
    }

    public function setPatientReminderEmail24HsSent()
    {
        return $query->where('appointment_at', '<', Carbon::now())->byAppointmentAtDesc();
    }

    public function sendPatientReminderEmail24Hs()
    {
        $recipient = $this->patient->user;

        if ($this->wasPatientReminderEmail24HsSent()) {
            Log::info("User #{$recipient->id} was already email notified about Appointment #{$this->id}. Skipping.");
            return false;
        } else {
            Log::info("Sending {$recipient->type} reminder to User #{$recipient->id} about Appointment #{$this->id}.");

            $transactionalEmailJob = TransactionalEmail::createJob(
                $recipient->email,
                'patient.appointment.reminder',
                [
                    'doctor_name' => $this->practitioner->user->fullName(),
                    'appointment_date' => $this->patientAppointmentAtDate()->format('l F j'),
                    'appointment_time' => $this->patientAppointmentAtDate()->format('h:i A'),
                    'appointment_time_zone' => $this->patientAppointmentAtDate()->format('T'),
                    'harvey_id' => $recipient->id,
                    'patient_first_name' => $recipient->first_name,
                    'phone_number' => $recipient->phone,
                ]
            );

            dispatch($transactionalEmailJob);

            $this->setPatientReminderEmail24HsSent();
            return true;
        }
    }

    /*
     * SCOPES
     */
    public function scopeRecent(Builder $builder)
    {
        return $builder->where('appointment_at', '<', Carbon::now())->orderBy('appointment_at', 'DESC');
    }

    public function scopeForPractitioner(Builder $builder, Practitioner $practitioner)
    {
        return $builder->where('practitioner_id', '=', $practitioner->id);
    }

    public function scopeByAppointmentAtDesc($query)
    {
        $query->orderBy('appointment_at', 'DESC');
    }

    public function scopeForPatient(Builder $builder, Patient $patient)
    {
        return $builder->where('patient_id', '=', $patient->id);
    }

    public function scopeWithinDateRange(Builder $builder, Carbon $startDate, Carbon $endDate)
    {
        return $builder->afterThan($startDate)->beforeThan($endDate);
    }

    public function scopeByAppointmentAtAsc(Builder $builder)
    {
        $builder->orderBy('appointment_at', 'ASC');
    }

    public function scopeBeforeThan(Builder $builder, Carbon $date)
    {
        return $builder->where('appointment_at', '<=', $date);
    }

    public function scopeAfterThan(Builder $builder, Carbon $date)
    {
        return $builder->where('appointment_at', '>=', $date);
    }

    public function scopeNot(Builder $builder, Appointment $appointment)
    {
        return $builder->where('appointments.id', '!=', $appointment->id);
    }

    public function scopePendingInTheNext24hs(Builder $builder)
    {
        return $builder->pending()->withinDateRange(Carbon::now(), Carbon::now()->addDay());
    }
}
