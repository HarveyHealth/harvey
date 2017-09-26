<?php

namespace App\Listeners;

use App\Events\LabOrderShipped;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateLabOrderWithShipmentLabel implements ShouldQueue
{
    public function handle(LabOrderShipped $event)
    {
        // try catch shippo info
        // $event->labOrder->labTests->map(sum dimensions)
        // call shippo
        // get data & save to db

        // Example: https://github.com/goshippo/shippo-php-client/blob/master/examples/basic-shipment.php
        $labTests = $event->labOrder->labTests()->get();

        foreach ($labTests as $labTest) {
          \Log::info('LabOrder has shipped with ' . $labTest->sku->id);
        }

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
        );

        /*
        $shipment = Shippo_Shipment::create(
        array(
          'address_from'=> $from_address,
          'address_to'=> $to_address,
          'parcels'=> array($parcel),
          'async'=> false,
          ));
        */
    }
}
