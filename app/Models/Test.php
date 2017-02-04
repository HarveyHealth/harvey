<?php

namespace App\Models;

use Aws\S3\Exception\S3Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Http\Traits\HasPatientAndPractitioner;

class Test extends Model
{
    use HasPatientAndPractitioner;

    protected $guarded = ['id'];


    public function tempResultsURL()
    {
        if (empty($this->results_key)) {
            return null;
        }
        
        try {
            $client = Storage::disk('s3')->getDriver()->getAdapter()->getClient();
            $expiry = "+1 hour";
            $command = $client->getCommand('GetObject', [
                'Bucket' => config('filesystems.disks.s3.bucket'),
                'Key'    => $this->results_key
            ]);
            $request = $client->createPresignedRequest($command, $expiry);
            
            return (string) $request->getUri();
        } catch (\Exception $e) {
            throw new S3Exception("Error generating pre-signed request for test results url: {$e->getMessage()}", 0, $e);
        }
    }

    /*
     * SCOPES
     */
    public function scopePending($query)
    {
        return $query->whereNull('results_key');
    }

    public function scopeRecent($query, $limit = 3)
    {
        return $query->whereNotNull('results_key')->limit($limit);
    }
}
