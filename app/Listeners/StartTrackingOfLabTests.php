<?php

namespace App\Listeners;

use App\Events\LabOrderShipped;
use App\Lib\TimeInterval;
use Illuminate\Contracts\Queue\ShouldQueue;
use Cache, Shippo_Error, Shippo_Track;

class StartTrackingOfLabTests implements ShouldQueue
{
    public function handle(LabOrderShipped $event)
    {
        $event->labOrder->labTests()->shipped()->each(function ($lab_test) {
            Cache::remember("track_for_lab_test_id_{$lab_test->id}", TimeInterval::hours(1)->toMinutes(), function () use ($lab_test) {
                try {
                    return Shippo_Track::create(['carrier' => $lab_test->carrier, 'tracking_number' => $lab_test->shipment_code])->__toArray(true);
                } catch (Shippo_Error $e) {
                    $shippo_error_detail = ucfirst($e->getJsonBody()['detail'] ?? 'No details');
                    ops_warning('Shippo warning!', "Can't start tracking of LabTest ID #{$lab_test->id}, Carrier: '{$lab_test->carrier}', Tracking number: #{$lab_test->shipment_code}. {$shippo_error_detail}.", 'engineering');
                    return [];
                }
            });
        });
    }
}
