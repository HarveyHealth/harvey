<?php

namespace App\Http\Controllers\Webhooks;

use App\Models\{LabOrder, LabTest};
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

    public function handleTransactionCreated()
    {
        return true;
    }

    public function handleTransactionUpdated()
    {
        return true;
    }

    public function handleTrackUpdated()
    {
        $payload = request()->all();
        $tracking_number = $payload['data']['tracking_number'];
        $tracking_status = $payload['data']['tracking_status']['status'];

        if (!empty($lab_test = LabTest::findByShipmentCode($tracking_number))) {
            $this->setLabTestStatus($lab_test, $tracking_status);
        } elseif (!empty($lab_order = LabOrder::findByShipmentCode($tracking_number))) {
            $this->setLabOrderStatus($lab_test, $tracking_status);
        } else {
            ops_warning('Shippo webhook error', "Can't find LabTest/Order with tracking number #{$tracking_number}", 'engineering');
            return false;
        }

        return true;
    }

    protected function setLabOrderStatus(LabOrder $lab_order, string $tracking_status)
    {
        $lab_order_old_status_id = $lab_order->status_id;

        switch ($tracking_status) {
            case 'DELIVERED':
                $lab_order->labTests->each->markAsReceived();
                $lab_order->setStatus()->save();
                if ($lab_order_old_status_id != $lab_order->status_id) {
                    ops_info('Shippo webhook', " LabOrder ID #{$lab_order->id} delivered.", 'operations');
                }
                break;
            case 'TRANSIT':
                $lab_order->labTests->each->markAsShipped();
                $lab_order->setStatus()->save();
                if ($lab_order_old_status_id != $lab_order->status_id) {
                    ops_info('Shippo webhook', " LabOrder ID #{$lab_order->id} in transit.", 'operations');
                }
                break;
            case 'FAILURE':
                ops_error('Shippo webhook error', "The Postal Service has identified a problem when processing LabOrder ID #{$lab_order->id}.", 'operations');
                break;
            case 'RETURNED':
                ops_warning('Shippo webhook warning', "LabOrder ID #{$lab_order->id} returned to sender.", 'operations');
                break;
            case 'UNKNOWN':
                ops_info('Shippo webhook', "Unknown status reported for LabOrder ID #{$lab_order->id}.", 'operations');
                break;
            default:
                ops_warning('Shippo webhook warning', "Status '{$tracking_status}' reported for LabOrder ID #{$lab_order->id}.", 'operations');
                return false;
                break;
        }

        return true;
    }

    protected function setLabTestStatus(LabTest $lab_test, string $tracking_status)
    {
        $lab_test_old_status_id = $lab_test->status_id;

        switch ($tracking_status) {
            case 'DELIVERED':
                $lab_test->markAsProcessing();
                if ($lab_test_old_status_id != $lab_test->status_id) {
                    ops_info('Shippo webhook', " LabTest ID #{$lab_test->id} delivered.", 'operations');
                }
                break;
            case 'TRANSIT':
                $lab_test->markAsMailed();
                if ($lab_test_old_status_id != $lab_test->status_id) {
                    ops_info('Shippo webhook', " LabTest ID #{$lab_test->id} in transit.", 'operations');
                }
                break;
            case 'FAILURE':
                ops_error('Shippo webhook error', "The Postal Service has identified a problem when processing LabTest ID #{$lab_test->id}.", 'operations');
                break;
            case 'RETURNED':
                ops_warning('Shippo webhook warning', "LabTest ID #{$lab_test->id} returned to sender.", 'operations');
                break;
            case 'UNKNOWN':
                ops_info('Shippo webhook', "Unknown status reported for LabTest ID #{$lab_test->id}.", 'operations');
                break;
            default:
                ops_warning('Shippo webhook warning', "Status '{$tracking_status}' reported for LabTest ID #{$lab_test->id}.", 'operations');
                return false;
                break;
        }

        return true;
    }
}
