<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\{Sluggable, SluggableScopeHelpers};
use Schema;

class SKU extends Model
{
    use Sluggable, SluggableScopeHelpers;

    protected $table = 'skus';

    protected $fillable = ['name', 'price', 'cost'];

    public function scopeItemType($query, $type)
    {
        return $query->where('item_type', $type);
    }

    public function scopelabtests($query)
    {
        return $query->where('item_type', '=', 'lab-test');
    }

    public function labTestInformation()
    {
        return $this->hasOne(LabTestInformation::class, 'sku_id', 'id');
    }

    public function sluggable()
    {
        if (!Schema::hasColumn($this->table, 'slug')) {
            return [];
        }

        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }
}
