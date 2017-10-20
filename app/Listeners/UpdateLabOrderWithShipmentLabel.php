<?php

namespace App\Listeners;

use App\Events\LabOrderShipped;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateLabOrderWithShipmentLabel implements ShouldQueue
{
    public function handle(LabOrderShipped $event)
    {
        // Example: https://github.com/goshippo/shippo-php-client/blob/master/examples/basic-shipment.php
        $lab_order = $event->labOrder;
        $labTests = $event->labOrder->labTests;
        $user = $event->labOrder->patient->user;

        // TODO: we need to get this from somewhere (found in shippo dashboard)
        // https://app.goshippo.com/carriers
        $carrier_account_id = '7fd02a1418fc4b3490ae6d5aa965ef6d'; // fake USPS account

        // Service level token
        // https://goshippo.com/docs/reference/php#servicelevels
        $carrier_service_level = 'usps_priority';

        // From Address
        $address_from = [
          'name' => 'Harvey, Inc',
          'company' => 'Harvey, Inc',
          'street1' => '12655 W Jefferson Blvd',
          'street2' => 'Suite #3-180',
          'city' => 'Playa Vista',
          'state' => 'CA',
          'zip' => '90066',
          'country' => 'US',
          'phone' => '+18006909989',
          'email' => 'support@goharvey.com',
          'test' => true,
        ];

        // To Address
        $address_to = [
          'name' => $user->full_name,
          'company' => '',
          'street1' => $lab_order->address_1,
          'street2' => $lab_order->address_2,
          'city' => $lab_order->city,
          'state' => $lab_order->state,
          'zip' => $lab_order->zip,
          'country' => 'US',
          'phone' => $user->phone,
          'email' => $user->email,
          'test' => true,
        ];

        $parcel_info = $labTests->pluck('sku')->map(function($sku) {
          return collect($sku->getAttributes())->only(['length', 'width', 'height', 'distance_unit', 'weight', 'mass_unit']);
        });

        $carriers = \Shippo_CarrierAccount::all();
        // \Log::info($carriers);

        // die();

        // generate shipping label transaction from a single API call
        $transaction = \Shippo_Transaction::create([
          'shipment' => [
            'address_to' => $address_to,
            'address_from' => $address_from,
            'parcels' => $parcel_info,
          ],
          'carrier_account' => $carrier_account_id,
          'servicelevel_token' => $carrier_service_level,
          'label_file_type' => 'PDF',
          'async' => false,
        ]);

        \Log::info($transaction);
        die();
    }
}
