<?php

namespace App\Http\Traits;

use Aws\S3\Exception\S3Exception;
use App\Lib\TimeInterval;
use Bugsnag, Cache, Storage;

trait HasKeyColumn
{
    public function getUrlAttribute()
    {
        if (empty($key = $this->key)) {
            return null;
        }

        $cacheKey = "aws-uri-for-{$key}";

        $uri = Cache::remember($cacheKey, TimeInterval::hours(12)->toMinutes(), function () use ($key) {
            try {
                $client = Storage::disk('s3')->getDriver()->getAdapter()->getClient();
                $command = $client->getCommand('GetObject', [
                    'Bucket' => config('filesystems.disks.s3.bucket'),
                    'Key' => $key,
                ]);
                $request = $client->createPresignedRequest($command, '+12 hours');
            } catch (S3Exception $e) {
                Bugsnag::notifyException($e);
                return null;
            }

            return (string) $request->getUri();
        });

        if (empty($uri)) {
            Cache::forget($cacheKey);
            return null;
        }

        return $uri;
    }
}
