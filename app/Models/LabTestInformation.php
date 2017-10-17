<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Lib\TimeInterval;
use Cache;

class LabTestInformation extends Model
{
    use SoftDeletes;

    protected $table = 'lab_tests_information';
    protected $fillable = ['description', 'image', 'sample', 'quote', 'lab_name'];

    public function sku()
    {
        return $this->belongsTo(SKU::class);
    }

    public static function allFromCache()
    {
        return Cache::remember('all_lab_tests_information', TimeInterval::days(1)->toMinutes(), function () {
            return static::whereNotNull('published_at')->orderBy('list_order', 'asc')->get()->load('sku');
        });
    }
}
