<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\HasPatientAndPractitioner;

class Test extends Model
{
    use HasPatientAndPractitioner;

    protected $guarded = ['id'];

    /*
     * This should return a limited use presigned S3 URL for HIPAA reasons
     */
    public function tempURL()
    {
        return $this->results_url;
    }

    /*
     * SCOPES
     */
    public function scopePending($query)
    {
        return $query->whereNull('results_url');
    }

    public function scopeRecent($query, $limit = 3)
    {
        return $query->whereNotNull('results_url')->limit($limit);
    }
}
