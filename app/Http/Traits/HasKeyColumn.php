<?php

namespace App\Http\Traits;

use Aws\S3\Exception\S3Exception;
use Bugsnag, Storage;

trait HasKeyColumn
{
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

        return (string) $request->getUri();
    }
}
