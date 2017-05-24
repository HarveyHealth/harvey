<?php

namespace App\Models;

use Carbon;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use App\Models\User;

class Message extends Model
{
    use Searchable;

    protected $dates = [
        'created_at',
        'read_at',
        'updated_at',
    ];

    protected $guarded = ['id', 'created_at', 'updated_at', 'read_at', 'is_admin', 'sender_user_id'];

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

    public function canUserRead(User $user)
    {
        return $this->sender == $user || $this->recipient == $user;
    }

    /*
     * Scopes
     */
    public function scopeFrom($query, User $user)
    {
        $userId = $user ? $user->id : 0;
        return $query->where('sender_user_id', $userId);
    }

    public function scopeTo($query, User $user)
    {
        $userId = $user ? $user->id : 0;
        return $query->where('recipient_user_id', $userId);
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

}
