<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\{Builder, Model, SoftDeletes};
use App\Http\Traits\{BelongsToPatientAndPractitioner, HasStatusColumn};
use App\Lib\TransactionalEmail;
use Lang, Log, View;

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

    protected $guarded = [
        'id',
        'created_at',
        'deleted_at',
        'status_id',
        'type_id',
        'updated_at',
    ];

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
            return $builder->whereHas('practitioner.user', function ($query) {
                $query->where('enabled', true);
            });
        });

        static::addGlobalScope('enabledPatient', function (Builder $builder) {
            return $builder->whereHas('patient.user', function ($query) {
                $query->where('enabled', true);
            });
        });
    }

    /*
     * Relationships
     */
    public function reminders()
    {
        return $this->hasMany(AppointmentReminder::class);
    }

    public function notes()
    {
        return $this->hasMany(PatientNote::class);
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

    public function wasReminderSent(User $user, int $typeId)
    {
        return (bool) $this->reminders()->where('type_id', $typeId)->toRecipient($user)->first();
    }

    public function setReminderSent(User $user, int $typeId)
    {
        $reminder = AppointmentReminder::make([
            'recipient_user_id' => $user->id,
            'type_id' => $typeId,
            'sent_at' => Carbon::now(),
        ]);

        return $this->reminders()->save($reminder);
    }

    public function sendClientReminderSms24Hs()
    {
        $time = $this->patientAppointmentAtDate()->format('h:i A');
        $timezone = $this->patientAppointmentAtDate()->format('T');

        return $this->sendReminderSms24Hs($this->patient->user, 'client_reminder_24_hs', compact('time', 'timezone'));
    }

    public function sendDoctorReminderSms24Hs()
    {
        $time = $this->practitionerAppointmentAtDate()->format('h:i A');
        $timezone = $this->practitionerAppointmentAtDate()->format('T');
        $patientName = $this->patient->user->full_name;

        return $this->sendReminderSms24Hs($this->practitioner->user, 'doctor_reminder_24_hs', compact('time', 'timezone', 'patientName'));
    }

    public function sendClientReminderEmail24Hs()
    {
        $templateData = [
            'appointment_date' => $this->patientAppointmentAtDate()->format('l F j'),
            'appointment_time' => $this->patientAppointmentAtDate()->format('h:i A'),
            'appointment_timezone' => $this->patientAppointmentAtDate()->format('T'),
            'patient_name' => $this->patient->user->full_name,
            'patient_state' => $this->patient->user->state,
            'practitioner_name' => $this->practitioner->user->full_name,
            'practitioner_state' => $this->practitioner->user->state,
        ];

        return $this->sendReminderEmail24Hs($this->patient->user, $templateData);
    }

    public function sendDoctorReminderEmail24Hs()
    {
        $templateData = [
                    'appointment_date' => $this->practitionerAppointmentAtDate()->format('l F j'),
                    'appointment_time' => $this->practitionerAppointmentAtDate()->format('h:i A'),
                    'appointment_timezone' => $this->practitionerAppointmentAtDate()->format('T'),
                    'patient_name' => $this->patient->user->full_name,
                    'patient_state' => $this->patient->user->state,
                    'practitioner_name' => $this->practitioner->user->full_name,
                    'practitioner_state' => $this->practitioner->user->state,
        ];

        return $this->sendReminderEmail24Hs($this->practitioner->user, $templateData);
    }

    public function sendReminderSms24Hs(User $user, string $templateName, array $templateData)
    {
        if ($this->wasReminderSent($user, AppointmentReminder::SMS_24_HS_NOTIFICATION_ID)) {
            Log::info("User #{$user->id} was already SMS notified about Appointment #{$this->id}. Skipping.");
            return false;
        }

        $time = $this->patientAppointmentAtDate()->format('h:i A');
        $timezone = $this->patientAppointmentAtDate()->format('T');

        $message = View::make("sms/{$templateName}")->with($templateData)->render();

        $user->sendText($message);

        $this->setReminderSent($user, AppointmentReminder::SMS_24_HS_NOTIFICATION_ID);

        return true;
    }

    public function sendReminderEmail24Hs(User $user, array $templateData)
    {
        if ($this->wasReminderSent($user, AppointmentReminder::EMAIL_24_HS_NOTIFICATION_ID)) {
            Log::info("User #{$user->id} was already email notified about Appointment #{$this->id}. Skipping.");
            return false;
        }

        $transactionalEmailJob = TransactionalEmail::createJob()
            ->setTo($user->email)
            ->setTemplate("{$user->type}.appointment.reminder")
            ->setTemplateModel($templateData);

        dispatch($transactionalEmailJob);

        $this->setReminderSent($user, AppointmentReminder::EMAIL_24_HS_NOTIFICATION_ID);

        return true;
    }

    /*
     * SCOPES
     */
    public function scopeUpcoming(Builder $builder, int $weeks = 4)
    {
        return $builder->afterThan(Carbon::now())->beforeThan(Carbon::now()->addWeeks($weeks))->byAppointmentAtAsc();
    }

    public function scopeRecent(Builder $builder)
    {
        return $builder->where('appointment_at', '<', Carbon::now())->byAppointmentAtDesc();
    }

    public function scopeForPractitioner(Builder $builder, Practitioner $practitioner)
    {
        return $builder->where('practitioner_id', '=', $practitioner->id);
    }

    public function scopeByAppointmentAtDesc(Builder $builder)
    {
        $builder->orderBy('appointment_at', 'DESC');
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
