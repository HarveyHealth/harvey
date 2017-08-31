<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable; 
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class SKU extends Model
{
	use Sluggable, SluggableScopeHelpers;

    protected $table = 'skus';

    public function scopeItemType($query, $type)
    {
    	return $query->where('item_type', $type);
    }

    public function labTestInformation()
    {
        return $this->hasOne(LabTestInformation::class, 'sku_id', 'id');
    }

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
}
