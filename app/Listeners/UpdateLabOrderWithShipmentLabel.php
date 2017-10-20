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

        // From Address
        $fromAddress = [
          'name' => '',
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
        $toAddress = [
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

        $parcelInfo = $labTests->pluck('sku')->map(function($sku) {
          return collect($sku->getAttributes())->only(['length', 'width', 'height', 'distance_unit', 'weight', 'mass_unit']);
        });

        $shipment = \Shippo_Shipment::create([
          'address_from' => $fromAddress,
          'address_to' => $toAddress,
          'parcels' => $parcelInfo,
          'async' => false,
        ]);

        // make sure addresses are valid
        $to_address_id = $shipment['address_to']['object_id'];
        $from_address_id = $shipment['address_from']['object_id'];

        $is_to_address_valid = \Shippo_Address::validate($to_address_id)['validation_results']['is_valid'];
        $is_from_address_valid = \Shippo_Address::validate($from_address_id)['validation_results']['is_valid'];

        if ($is_to_address_valid && $is_from_address_valid) {
          if (!empty($shipment['rates'])) {
            // for now I'll pick the first rate
            $rate = $shipment['rates'][0];

            $transaction = \Shippo_Transaction::create([
              'rate' => $rate['object_id'],
              'async' => false,
            ]);

            \Log::info($transaction);
          } else {
            \Log::error('There are no avaiable shipping rates'); // TODO: more helpful error
          }
        } else {
          \Log::error('Address is invalid'); // TODO: more helpful error
        }

        die();

        // Rates are stored in the `rates` array
        // The details on the returned object are here: https://goshippo.com/docs/reference#rates
        // Get the first rate in the rates results for demo purposes.
        // $rate = $shipment['rates'][0];
        // Purchase the desired rate with a transaction request
        // Set async=false, indicating that the function will wait until the carrier returns a shipping label before it returns
        // $transaction = Shippo_Transaction::create(array(
        //     'rate'=> $rate['object_id'],
        //     'async'=> false,
        // ));
        // Print the shipping label from label_url
        // Get the tracking number from tracking_number
        // if ($transaction['status'] == 'SUCCESS'){
        //     echo "--> " . "Shipping label url: " . $transaction['label_url'] . "\n";
        //     echo "--> " . "Shipping tracking number: " . $transaction['tracking_number'] . "\n";
        // } else {
        //     echo "Transaction failed with messages:" . "\n";
        //     foreach ($transaction['messages'] as $message) {
        //         echo "--> " . $message . "\n";
        //     }
        // }
    }
}
