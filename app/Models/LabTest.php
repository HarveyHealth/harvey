<?php

namespace App\Models;

use App\Http\Traits\HasStatusColumn;
use App\Models\LabOrder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LabTest extends Model
{
    use SoftDeletes, HasStatusColumn;

    const SHIPPED_STATUS_ID = 0;
    const CANCELED_STATUS_ID = 1;
    const COMPLETE_STATUS_ID = 2;
    const MAILED_STATUS_ID = 3;
    const PENDING_STATUS_ID = 4;
    const PROCESSING_STATUS_ID = 5;
    const RECEIVED_STATUS_ID = 6;

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
        self::MAILED_STATUS_ID => 'mailed',
        self::PENDING_STATUS_ID => 'pending',
        self::PROCESSING_STATUS_ID => 'processing',
        self::RECEIVED_STATUS_ID => 'received',
        self::SHIPPED_STATUS_ID => 'shipped',
    ];

    public function labOrder()
    {
        return $this->belongsTo(LabOrder::class);
    }

    public function patient()
    {
        return $this->labOrder->patient();
    }

    public function practitioner()
    {
        return $this->labOrder->practitioner();
    }

    public function isLocked()
    {
        return $this->labOrder->isComplete();
    }

    public function isComplete()
    {
        return $this->status_id == self::COMPLETE_STATUS_ID;
    }

    public function isCanceled()
    {
        return $this->status_id == self::CANCELED_STATUS_ID;
    }

    public function isNotLocked()
    {
        return !$this->isLocked();
    }
}
