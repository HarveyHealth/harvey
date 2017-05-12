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
       ];
    }

    /*
     * Model functions
     */

    public function touchReadAt()
    {
        $this->read_at = Carbon::now();

        return $this->save();
    }
}
