<?php

namespace App\Models;

use App\Http\Traits\BelongsToPatient;
use Illuminate\Database\Eloquent\{Model, Builder};
use Laravel\Scout\Searchable;
use Carbon;

class SoapNote extends Model
{
    use BelongsToPatient, Searchable;

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

    /**
     * indexable data array for the model.
     */
    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'patient_name' => $this->patient->user->full_name,
            'subjective' => $this->subjective,
            'objective' => $this->objective,
            'assessment' => $this->assessment,
            'plan' => $this->plan,
       ];
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('enabledUser', function (Builder $builder) {
            return $builder->whereHas('patient.user', function ($query) {
                $query->where('enabled', true);
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
