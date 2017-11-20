<?php

namespace App\Models;

use App\Http\Traits\{BelongsToPatient, HasKeyColumn};
use Illuminate\Database\Eloquent\{Builder, Model, SoftDeletes};
use Carbon;

class Attachment extends Model
{
    use BelongsToPatient, HasKeyColumn, SoftDeletes;

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $guarded = [
        'id',
        'created_at',
        'created_by_user_id',
        'updated_at',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('enabledUser', function (Builder $builder) {
            return $builder->whereHas('patient.user', function ($builder) {
                $builder->where('enabled', true);
            });
        });
    }

    /*
     * Relationships
     */

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }
}
