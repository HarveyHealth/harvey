<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Model, Builder};
use Laravel\Scout\Searchable;
use Carbon;

class Message extends Model
{
    use Searchable;

    protected $dates = [
        'created_at',
        'read_at',
        'updated_at',
    ];

    protected $guarded = ['id', 'created_at', 'updated_at', 'read_at', 'is_admin', 'sender_user_id'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('enabledSender', function (Builder $builder) {
            return $builder->whereHas('sender', function (Builder $builder){
                $builder->where('users.enabled', true);
            });
        });

        static::addGlobalScope('enabledRecipient', function (Builder $builder) {
            return $builder->whereHas('recipient', function (Builder $builder){
                $builder->where('users.enabled', true);
            });
        });
    }

    /*
     * Relationships
     */

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_user_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_user_id');
    }

    /*
     * Custom attributes
     */

    public function getIsSenderAdminAttribute()
    {
        return (bool) $this->is_admin;
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'message' => $this->message,
            'subject' => $this->subject,
       ];
    }

    /*
     * Model functions
     */

    public function setReadAt()
    {
        $this->read_at = $this->read_at ?: Carbon::now();

        return $this;
    }

    /*
     * Scopes
     */
    public function scopeFrom(Builder $builder, User $user)
    {
        $userId = $user ? $user->id : 0;
        return $builder->where('sender_user_id', $userId);
    }

    public function scopeTo(Builder $builder, User $user)
    {
        $userId = $user ? $user->id : 0;
        return $builder->where('recipient_user_id', $userId);
    }

    public function scopeSenderOrRecipient(Builder $builder, User $user)
    {
        $userId = $user ? $user->id : 0;
        return $builder->where(function (Builder $builder) use ($userId)
            {
                $builder->where('recipient_user_id', $userId)
                      ->orWhere('sender_user_id', $userId);
            });
    }

    public function scopeUnread(Builder $builder)
    {
        return $builder->whereNull('read_at');
    }

    public function scopeCreatedAfter(Builder $builder, Carbon $date)
    {
        return $builder->where('created_at', '>', $date);
    }

    public function scopeCreatedBefore(Builder $builder, Carbon $date)
    {
        return $builder->where('created_at', '<', $date);
    }

    public function scopeIdGreaterThan(Builder $builder, int $id)
    {
        return $builder->where('id', '>', $id);
    }
}
