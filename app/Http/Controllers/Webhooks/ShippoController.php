<?php

namespace App\Http\Controllers\Webhooks;

use App\Models\LabOrder;
use Log, ResponseCode;

class ShippoController extends BaseWebhookController
{
    public function handle()
    {
        Log::info('New incoming payload on Shippo webhook endpoint.');

        $payload = request()->all();

        $methodName = 'handle' . studly_case($payload['event']);

        if (method_exists($this, $methodName)) {
            $this->$methodName();
        } else {
            ops_warning('Shippo webhook method not handled', "Type: {$payload['event']}", 'engineering');
        }

        return response('A-OK!', ResponseCode::HTTP_OK);
    }

    public function handleTrackUpdated()
    {
        $payload = request()->all();

        if (empty($lab_order = LabOrder::findByShipmentCode($payload['data']['tracking_number']))) {
            ops_warning('Shippo webhook error', "Can't find LabOrder with tracking number #{$payload['data']['tracking_number']}", 'engineering');
            return false;
        }

        switch ($payload['data']['tracking_status']) {
            case 'DELIVERED':
                $lab_order->markAsReceived();
                ops_info('Shippo webhook', " LabOrder ID #{$lab_order->id} delivered.", 'operations');
                break;
            case 'TRANSIT':
                $lab_order->markAsShipped();
                ops_info('Shippo webhook', " LabOrder ID #{$lab_order->id} in transit.", 'operations');
                break;
            case 'FAILURE':
                ops_error('Shippo webhook error', "The Postal Service has identified a problem when processing LabOrder ID #{$lab_order->id}.", 'operations');
                break;
            case 'RETURNED':
                ops_info('Shippo webhook', " LabOrder ID #{$lab_order->id} returned to sender.", 'operations');
                break;
            case 'UNKNOWN':
                ops_warning('Shippo webhook warning', "Unknown status when processing LabOrder ID #{$lab_order->id}.", 'operations');
                break;
            default:
                ops_warning('Shippo webhook warning', "Unknown status '{$payload['data']['tracking_status']}' when processing LabOrder ID #{$lab_order->id}.", 'engineering');
                return false;
                break;
        }

        return true;
    }
}
