<?php

namespace App\Lib;

use App\Models\Patient;
use App\Models\Invoice;
use App\Events\ChargeFailed;
use Stripe\Stripe;

class Cashier
{
	public function generatePatientInvoiceForInvoiceable($invoiceable)
	{
		$invoice_data = $invoiceable->dataForInvoice();

        $invoice = Invoice::newInvoiceWithData($invoice_data);
        $invoice->patient_id = $invoiceable->patient_id;
        $invoice->calculateTotals();

        return $invoice;
	}

	public function chargePatientForInvoice(Invoice $invoice)
	{
		// make sure invoice hasn't already been processed
		if ($invoice->isPaid())
			return;

		// calculate the subtotals, if needed
        $invoice->calculateTotals();

        // no need to charge if there's no balance
        if ($invoice->amount > 0) {

        	// convert for stripe
        	$amount = $amount * 100;

        	$data = [
        		'source' => $invoice->patient->stripe_customer_id,
        		'amount' => $amount,
        		'currency' => 'usd',
        		'description' => $invoice->description
        	];

        	try {

        		$charge = \Stripe\Charge::create($data);
        		
        	} catch (Exception $e) {
        		
        		ops_error('Stripe Exception','Could not change for Invoice ' . $invoice->id . ': ' . $exception->getMessage());
        		event(new ChargeFailed($invoice));
        	}

        } else {

        }

        $invoice->paid_on = date('Y-m-d H:i:s');
        $invoice->save();

	}
}