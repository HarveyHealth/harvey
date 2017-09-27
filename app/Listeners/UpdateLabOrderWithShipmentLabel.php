<?php

namespace App\Listeners;

use App\Events\LabOrderShipped;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateLabOrderWithShipmentLabel implements ShouldQueue
{
    public function handle(LabOrderShipped $event)
    {
        // Example: https://github.com/goshippo/shippo-php-client/blob/master/examples/basic-shipment.php
        $labTests = $event->labOrder->labTests()->get();
        $patientInfo = $event->labOrder->patient->user;
        $parcels = array();

        // From Address
        $fromAddress = array(
          'name' => '',
          'company' => 'Harvey, Inc',
          'street1' => '12655 W Jefferson Blvd',
          'street2' => '#3-180',
          'city' => 'Playa Vista',
          'state' => 'CA',
          'zip' => '90094',
          'country' => 'US',
          'phone' => '+1 800 690 9989',
          'email' => 'support@goharvey.com',
          'test' => true,
        );

        // To Address
        $toAddress = array(
          'name' => $patientInfo->first_name . ' ' . $patientInfo->last_name,
          'company' => '',
          'street1' => $patientInfo->address_1,
          'street2' => $patientInfo->address_2,
          'city' => $patientInfo->city,
          'state' => $patientInfo->state,
          'zip' => $patientInfo->zip,
          'country' => 'US',
          'phone' => $patientInfo->phone,
          'email' => $patientInfo->email,
          'test' => true,
        );

        // Parcel info
        $parcelInfo = $labTests->map(function($labTest) {
          $parcelData = array(
            'length' => $labTest->sku->length,
            'width' => $labTest->sku->width,
            'height' => $labTest->sku->height,
            'distance_unit' => $labTest->sku->distance_unit,
            'weight' => $labTest->sku->weight,
            'mass_unit' => $labTest->sku->mass_unit
          );

          // add it to the parcels array
          return $parcels[] = $parcelData;
        });

        // Create a Shippo Shipment object
        $shipment = \Shippo_Shipment::create(
        array(
          'address_from' => $fromAddress,
          'address_to' => $toAddress,
          'parcels' => $parcelInfo,
          'async' => false,
          ));

        \Log::info($shipment);

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
