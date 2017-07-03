<?php
namespace App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Lang;

class Appointment extends Model
{
    use SoftDeletes;
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

    public function getStatusAttribute()
    {
        return empty(self::STATUSES[$this->status_id]) ? null : self::STATUSES[$this->status_id];
    }

    public function setStatusAttribute($value)
    {
        if (false !== ($key = array_search($value, self::STATUSES))) {
            $this->status_id = $key;
        }
        return $value;
    }

    public function getStatusFriendlyName()
    {
        return $this->status ? Lang::get("appointments.status.{$this->status}") : null;
    }

    public function isPending()
    {
        return $this->status_id == self::PENDING_STATUS_ID;
    }
    /*
     * SCOPES
     */
    public function scopeUpcoming($query, $weeks = 2)
    {
        $end_date = Carbon::now()->addWeeks($weeks);
        return $query->where('appointment_at', '>', Carbon::now())
                    ->where('appointment_at', '<=', $end_date->toDateTimeString())
                    ->orderBy('appointment_at', 'ASC');
    }

    public function scopeRecent($query)
    {
        return $query->where('appointment_at', '<', Carbon::now())->orderBy('appointment_at', 'DESC');
    }

    public function scopeForPractitioner($query, Practitioner $practitioner)
    {
        return $query->where('practitioner_id', '=', $practitioner->id);
    }

    public function scopeForPatient($query, Patient $patient)
    {
        return $query->where('patient_id', '=', $patient->id);
    }

    public function scopeWithinDateRange($query, Carbon $startDate, Carbon $endDate)
    {
        return $query->afterThan($startDate)->beforeThan($endDate);
    }

    public function scopeByAppointmentAtAsc($query)
    {
        $query->orderBy('appointment_at', 'ASC');
    }

    public function scopeBeforeThan($query, Carbon $date)
    {
        return $query->where('appointment_at', '<=', $date);
    }

    public function scopeAfterThan($query, Carbon $date)
    {
        return $query->where('appointment_at', '>=', $date);
    }

    public function scopeNoShowPatient($query)
    {
        return $query->where('status_id', self::NO_SHOW_PATIENT_STATUS_ID);
    }

    public function scopeNoShowDoctor($query)
    {
        return $query->where('status_id', self::NO_SHOW_DOCTOR_STATUS_ID);
    }

    public function scopeGeneralConflict($query)
    {
        return $query->where('status_id', self::GENERAL_CONFLICT_STATUS_ID);
    }

    public function scopeNot($query, Appointment $appointment)
    {
        return $query->where('appointments.id', '!=', $appointment->id);
    }

}
