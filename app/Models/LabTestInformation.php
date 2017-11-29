<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use App\Lib\TimeInterval;
use App\Http\Traits\HasVisibilityColumn;
use Cache;

class LabTestInformation extends Model
{
    use HasVisibilityColumn, SoftDeletes;

    const PUBLIC_VISIBILITY_ID = 0;
    const PATIENTS_VISIBILITY_ID = 1;
    const PRACTITIONERS_VISIBILITY_ID = 2;
    const ADMINS_VISIBILITY_ID = 3;

    const VISIBILITIES = [
        self::PUBLIC_VISIBILITY_ID => 'public',
        self::PATIENTS_VISIBILITY_ID => 'patients',
        self::PRACTITIONERS_VISIBILITY_ID => 'practitioners',
        self::ADMINS_VISIBILITY_ID => 'admins',
    ];

    const PUBLIC_CACHE_KEY = 'public_lab_tests_information';

    protected $table = 'lab_tests_information';
    protected $fillable = ['description', 'image', 'sample', 'quote', 'lab_name', 'visibility_id'];

    public function sku()
    {
        return $this->belongsTo(SKU::class);
    }

    public static function publicFromCache()
    {
        return Cache::remember(self::PUBLIC_CACHE_KEY, TimeInterval::days(1)->toMinutes(), function () {
            return LabTestInformation::public()->orderBy('list_order', 'asc')->get();
        });
    }
}
