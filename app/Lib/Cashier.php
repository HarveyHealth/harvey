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
                if (!in_array('App\Http\Traits\Invoiceable', class_uses($invoiceable)))
                        throw new Exception('Invalid class ' . get_class($invoiceable) . '. Does not use invoiceable trait. ');

		$invoice_data = $invoiceable->dataForInvoice();

                $invoice = Invoice::newInvoiceWithData($invoice_data);

                // record the invoice ID
                $invoiceable->invoice_id = $invoice->id;
                $invoiceable->save();

                return $invoice;
	}

	public function chargePatientForInvoice(Invoice $invoice)
	{
		// make sure invoice hasn't already been processed
		if ($invoice->isPaid())
			return;

		// calculate the subtotals, if needed
                $invoice->calculateTotals();

                $transaction = new \App\Models\Transaction;
                $transaction->invoice_id = $invoice->id;
                $transaction->amount = $invoice->amount;
                $transaction->patient_id = $invoice->patient_id;

                // no need to charge if there's no balance
                if ($invoice->amount > 0) {

                	// convert for stripe
                	$amount = $amount * 100;

                	$data = [
                		'customer' => $invoice->patient->stripe_customer_id,
                                // 'source' => $invoice->patient->stripe_source?
                		'amount' => $amount,
                		'currency' => 'usd',
                		'description' => $invoice->description
                	];

                	try {

                		$charge = \Stripe\Charge::create($data);

                                if ($charge->paid) {

                                        $transaction->transaction = $charge->id;
                                        $transaction->date = date('Y-m-d H:i:s', $transaction->created);
                                        $transaction->success = true;

                                        $invoice->paid_on = date('Y-m-d H:i:s');
                                        $invoice->transaction_id = $transaction->id;

                                } else {

                                        $transaction->transaction = $charge->id;
                                        $transaction->gateway = 'stripe';
                                        $transaction->date = date('Y-m-d H:i:s');
                                        $transaction->success = false;

                                        // fire an event
                                        event(new ChargeFailed($invoice, null, $transaction));
                                }
                		
                	} catch (\Exception $e) {

                                $transaction->gateway = 'stripe';
                                $transaction->response_message = $e->getMessage();
                                $transaction->date = date('Y-m-d H:i:s');
                                $transaction->success = false;
                		
                                // report it to engineering because it was an exception
                		ops_error('Stripe Exception','Could not charge for Invoice ' . $invoice->id . ': ' . $exception->getMessage());

                                // fire an event
                                event(new ChargeFailed($invoice, $exception));
                	}

                } else {

                        $invoice->paid_on = date('Y-m-d H:i:s');
                }

                $transaction->save();
                $invoice->save();
	}
}