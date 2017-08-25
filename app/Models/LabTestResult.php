<?php

namespace App\Models;

use App\Models\LabTest;
use Aws\S3\Exception\S3Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Bugsnag, Storage;

class LabTestResult extends Model
{
    use SoftDeletes;

    protected $table = 'lab_tests_results';

    protected $guarded = ['id', 'key', 'lab_test_id'];

    public function labTest()
    {
        return $this->belongsTo(LabTest::class);
    }

    public function getUrlAttribute()
    {
        if (empty($this->key)) {
            return null;
        }

        try {
            $client = Storage::disk('s3')->getDriver()->getAdapter()->getClient();
            $command = $client->getCommand('GetObject', [
                'Bucket' => config('filesystems.disks.s3.bucket'),
                'Key' => $this->key,
            ]);
            $request = $client->createPresignedRequest($command, '+1 hour');
        } catch (S3Exception $e) {
            Bugsnag::notifyException($e);
            return null;
        }

        return $request->getUri();
    }
}
