<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\{Builder, Model, SoftDeletes};

class AppointmentReminder extends Model
{
    use SoftDeletes;

    protected $table = 'appointments_reminders';

    protected $dates = [
        'created_at',
        'deleted_at',
        'sent_at',
        'updated_at',
    ];

    protected $guarded = ['created_at', 'deleted_at', 'updated_at'];

    const EMAIL_24_HS_NOTIFICATION_ID = 0;
    const SMS_24_HS_NOTIFICATION_ID = 1;
    const INTAKE_SMS_12_HS_NOTIFICATION_ID = 2;

    const TYPES = [
        self::EMAIL_24_HS_NOTIFICATION_ID => 'email_24hs',
        self::SMS_24_HS_NOTIFICATION_ID => 'sms_24hs',
        self::INTAKE_SMS_12_HS_NOTIFICATION_ID => 'intake_sms_12hs',
    ];

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_user_id');
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

    public function scopeToRecipient(Builder $builder, User $user)
    {
        return $builder->where('recipient_user_id', $user->id);
    }
}
