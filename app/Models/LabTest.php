<?php

namespace App\Models;

use App\Http\Traits\HasStatusColumn;
use App\Models\{LabOrder, SKU};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class LabTest extends Model
{
    use SoftDeletes, HasStatusColumn;

    const CANCELED_STATUS_ID = 1;
    const COMPLETE_STATUS_ID = 7;
    const CONFIRMED_STATUS_ID = 2;
    const MAILED_STATUS_ID = 5;
    const PROCESSING_STATUS_ID = 6;
    const RECEIVED_STATUS_ID = 4;
    const RECOMMENDED_STATUS_ID = 0;
    const SHIPPED_STATUS_ID = 3;

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
        self::CONFIRMED_STATUS_ID => 'confirmed',
        self::MAILED_STATUS_ID => 'mailed',
        self::PROCESSING_STATUS_ID => 'processing',
        self::RECEIVED_STATUS_ID => 'received',
        self::RECOMMENDED_STATUS_ID => 'recommended',
        self::SHIPPED_STATUS_ID => 'shipped',
    ];

    public function labOrder()
    {
        return $this->belongsTo(LabOrder::class);
    }

    public function sku()
    {
        return $this->belongsTo(SKU::class);
    }

    public function information()
    {
        return $this->sku->labTestInformation();
    }

    public function patient()
    {
        return $this->labOrder->patient();
    }

    public function practitioner()
    {
        return $this->labOrder->practitioner();
    }

    public function scopePatientOrPractitioner($query, User $user)
    {
        return $query->whereHas('labOrder', function ($query) use ($user) {
            $query->patientOrPractitioner($user);
        });
    }

    public function scopeShipped(Builder $builder)
    {
        return $builder->where('status_id', self::SHIPPED_STATUS_ID);
    }
}
