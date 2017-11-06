<?php

namespace App\Models;

use App\Models\LabTest;
use App\Http\Traits\HasKeyColumn;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Laravel\Scout\Searchable;

class LabTestResult extends Model
{
    use HasKeyColumn, SoftDeletes, Searchable;

    protected $table = 'lab_tests_results';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $guarded = [
        'id',
        'lab_test_id',
    ];

    /**
     * indexable data array for the model.
     */
    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'lab_test' => $this->labTest->sku->name,
            'key' => $this->key,
            'notes' => $this->notes,
       ];
    }

    public function labTest()
    {
        return $this->belongsTo(LabTest::class);
    }
}
