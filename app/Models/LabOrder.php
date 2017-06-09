<?php

namespace App\Models;

use App\Http\Traits\BelongsToPatientAndPractitioner;
use App\Http\Traits\HasStatusColumn;
use App\Models\LabTest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LabOrder extends Model
{
    use SoftDeletes, HasStatusColumn, BelongsToPatientAndPractitioner;

    const PENDING_STATUS_ID = 0;
    const CANCELED_STATUS_ID = 1;
    const COMPLETE_STATUS_ID = 2;

    protected $dates = [
        'completed_at',
        'created_at',
        'deleted_at',
    ];

    protected $guarded = [
        'id',
        'created_at',
        'completed_at',
        'deleted_at',
        'status_id',
    ];

    const STATUSES = [
        self::CANCELED_STATUS_ID => 'canceled',
        self::COMPLETE_STATUS_ID => 'complete',
        self::PENDING_STATUS_ID => 'pending',
    ];

    public function labTests()
    {
        return $this->hasMany(LabTest::class);
    }

    public function isComplete()
    {
        return self::COMPLETE_STATUS_ID == $this->status_id;
    }

    public function markAsComplete()
    {
        $this->status_id = self::COMPLETE_STATUS_ID;

        return $this->save();
    }

    public function areLabTestsComplete()
    {
        foreach ($this->labTests as $labTest) {
            if ($labTest->isNotComplete()) {
                return false;
            }
        }

        return true;
    }
}
