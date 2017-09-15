<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\{Builder, Model, SoftDeletes};
use App\Http\Traits\{BelongsToPatientAndPractitioner, HasStatusColumn, Invoiceable};
use App\Lib\{GoogleCalendar, TimeInterval, TransactionalEmail};
use Bugsnag, Cache, Exception, Lang, Log, View;

class Appointment extends Model
{
    use SoftDeletes, HasStatusColumn, BelongsToPatientAndPractitioner, Invoiceable;

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
        'google_calendar_event_id',
        'status_id',
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

    public function getGoogleMeetLinkAttribute()
    {
        if (empty($this->google_calendar_event_id)) {
            return null;
        }

        return Cache::remember("google-meet-link-appointment-id-{$this->id}", TimeInterval::weeks(2)->toMinutes(), function () {
            return GoogleCalendar::getEvent($this->google_calendar_event_id)->hangoutLink;
        });
    }

    public function isLocked()
    {
        return $this->hoursToStart() <= self::CANCEL_LOCK;
    }

    public function isNotLocked()
    {
        return !$this->isLocked();
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
        $templateData = [
            'doctor_name' => $this->practitioner->user->full_name,
            'time' => $this->patientAppointmentAtDate()->format('h:i A'),
            'timezone' => $this->patientAppointmentAtDate()->format('T'),
        ];

        return $this->sendReminderSms($this->patient->user, 'client_reminder_24_hs', $templateData, AppointmentReminder::SMS_24_HS_NOTIFICATION_ID);
    }

    public function sendDoctorReminderSms24Hs()
    {
        $templateData = [
            'time' => $this->practitionerAppointmentAtDate()->format('h:i A'),
            'timezone' => $this->practitionerAppointmentAtDate()->format('T'),
            'patient_name' => $this->patient->user->full_name,
        ];

        return $this->sendReminderSms($this->practitioner->user, 'doctor_reminder_24_hs', $templateData, AppointmentReminder::SMS_24_HS_NOTIFICATION_ID);
    }

    public function sendClientReminderEmail24Hs()
    {
        return false;

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
        return false;

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

    public function sendClientIntakeReminderSms12Hs()
    {
        $templateData = [
            'doctor_name' => $this->practitioner->user->full_name,
            'intake_link' => "https://goharvey.intakeq.com/new/Qqy0mI/DpjPFg?harveyID={$this->patient->user->id}",
            'time' => $this->patientAppointmentAtDate()->format('h:i A'),
            'timezone' => $this->patientAppointmentAtDate()->format('T'),
        ];

        return $this->sendReminderSms($this->patient->user, 'client_intake_reminder_12_hs', $templateData, AppointmentReminder::INTAKE_SMS_12_HS_NOTIFICATION_ID);
    }

    public function sendClientReminderSms1Hs()
    {
        $templateData = [
            'meet_link' => $this->google_meet_link,
            'time' => $this->patientAppointmentAtDate()->format('h:i A'),
            'timezone' => $this->patientAppointmentAtDate()->format('T'),
        ];

        return $this->sendReminderSms($this->patient->user, 'client_reminder_1_hs', $templateData, AppointmentReminder::SMS_1_HS_NOTIFICATION_ID);
    }

    public function sendDoctorReminderSms1Hs()
    {
        $templateData = [
            'patient_name' => $this->patient->user->full_name,
            'patient_phone' => $this->patient->user->phone,
            'meet_link' => $this->google_meet_link,
            'time' => $this->practitionerAppointmentAtDate()->format('h:i A'),
            'timezone' => $this->practitionerAppointmentAtDate()->format('T'),
        ];

        return $this->sendReminderSms($this->practitioner->user, 'doctor_reminder_1_hs', $templateData, AppointmentReminder::SMS_1_HS_NOTIFICATION_ID);
    }

    public function sendReminderSms(User $user, string $templateName, array $templateData, int $typeId)
    {
        if ($this->wasReminderSent($user, $typeId)) {
            Log::info("User #{$user->id} was already SMS notified about Appointment #{$this->id} using Template #{$templateName}. Skipping.");
            return false;
        }

        $message = View::make("sms/{$templateName}")->with($templateData)->render();

        $user->sendText($message);

        $this->setReminderSent($user, $typeId);

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

    public function createCalendarEvent()
    {
        if (!empty($this->google_calendar_event_id)) {
            return false;
        }

        try {
            $event = GoogleCalendar::addEvent($this->getEventParams());
        } catch (Exception $e) {
            Bugsnag::notifyException($e);
            ops_warning('Appointment@createCalendarEvent', "Can't add Appointment #{$this->id} to Google Calendar.");
            return false;
        }

        $this->google_calendar_event_id = $event->id;

        Cache::remember("google-meet-link-appointment-id-{$this->id}", TimeInterval::weeks(2)->toMinutes(), function () use ($event) {
            return $event->hangoutLink;
        });

        try {
            $update = GoogleCalendar::updateEvent($event->id, $this->getEventParams($event->hangoutLink));
        } catch (Exception $e) {
            Bugsnag::notifyException($e);
            ops_warning('Appointment@createCalendarEvent', "Can't add Meet link into event description to Appointment #{$this->id}.");
            return false;
        }

        return $update;
    }

    public function getEventParams(string $hangoutLink = '')
    {
        $description = empty($this->reason_for_visit) ? "Reason for visit not specified" : $this->reason_for_visit;
        $description = trim($description, '.').'.';
        $description .= "\n{$this->patient->user->email}";

        if (!empty($hangoutLink)) {
            $description .= "\n{$hangoutLink}";
        }

        return [
            'summary' => $this->patient->user->full_name,
            'description' => $description,
            'start' => [
                'dateTime' => $this->practitionerAppointmentAtDate()->toW3cString(),
                'timeZone' => $this->practitioner->timezone,
            ],
            'end' => [
                'dateTime' => $this->practitionerAppointmentAtDate()->addHour()->toW3cString(),
                'timeZone' => $this->practitioner->timezone,
            ],
            'attendees' => [
                ['email' => $this->practitioner->user->email],
            ],
            'reminders' => [
                'useDefault' => true,
            ],
            'status' => 'confirmed',
        ];
    }

    public function deleteFromCalendar()
    {
        if (empty($this->google_calendar_event_id)) {
            return false;
        }

        try {
            GoogleCalendar::deleteEvent($this->google_calendar_event_id);
        } catch (Exception $e) {
            Bugsnag::notifyException($e);
            ops_warning('Appointment@deleteFromCalendar', "Can't delete Appointment #{$this->id} from Google Calendar.");
            return false;
        }

        $this->google_calendar_event_id = null;
        $this->save();

        return true;
    }

    public function updateOnCalendar()
    {
        if (empty($this->google_calendar_event_id)) {
            return $this->createCalendarEvent();
        }

        try {
            GoogleCalendar::updateEvent($this->google_calendar_event_id, $this->getEventParams());
        } catch (Exception $e) {
            Bugsnag::notifyException($e);
            ops_warning('Appointment@updateOnCalendar', "Can't update Appointment #{$this->id} on Google Calendar.");
            return false;
        }

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

    public function scopePendingInTheNext12hs(Builder $builder)
    {
        return $builder->pending()->withinDateRange(Carbon::now(), Carbon::now()->addHours(12));
    }

    public function scopePendingInTheNextHour(Builder $builder)
    {
        return $builder->pending()->withinDateRange(Carbon::now(), Carbon::now()->addHour());
    }

    public function scopeEmptyPatientIntake(Builder $builder)
    {
        return $builder->whereHas('patient.user', function ($query) {
            $query->whereNull('intake_completed_at');
        });
    }

    public function dataForInvoice()
    {
        $minutes = 30 == $this->duration_in_minutes ? '30' : '60';

        $sku = SKU::findBySlug("{$minutes}-minute-consultation");

        $description = "{$sku->name}, Appointment #{$this->id} with {$this->practitioner->user->full_name} on {$this->appointment_at->format('n/j/y')}";

        return [
            'patient_id' => $this->patient_id,
            'practitioner_id' => $this->practitioner_id,
            'description' => $description,
            'discount_code_id' => $this->discount_code_id,
            'invoice_items' => [
                [
                    'item_id' => $this->id,
                    'item_class' => get_class($this),
                    'description' => $description,
                    'amount' => $sku->price,
                    'sku_id' => $sku->id,
                ],
            ],
        ];
    }
}
