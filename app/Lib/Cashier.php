<?php

namespace App\Lib;

use App\Models\Patient;
use App\Models\Invoice;
use App\Events\ChargeFailed;
use Stripe\Stripe;
use App\Jobs\ChargePatientForInvoice;
use Carbon\Carbon;
use App\Models\Invoice;

class Cashier
{
	public function generatePatientInvoiceForInvoiceable($invoiceable)
	{
                if (!in_array('App\Http\Traits\Invoiceable', class_uses($invoiceable)))
                        throw new Exception('Invalid class ' . get_class($invoiceable) . '. Does not use invoiceable trait. ');

                $invoice = $invoiceable->invoice;
                if (!$invoice) {

                        $invoice_data = $invoiceable->dataForInvoice();

                        $invoice = Invoice::newInvoiceWithData($invoice_data);

                        // record the invoice ID
                        $invoiceable->invoice_id = $invoice->id;
                        $invoiceable->save();
                }                

                return $invoice;
	}

	public function chargePatientForInvoice(Invoice $invoice)
	{
		// make sure invoice hasn't already been processed
		if ($invoice->isOutstanding())
			return;

		// calculate the subtotals, if needed
                $invoice->calculateTotals();

                // no need to charge if there's no balance
                if ($invoice->amount > 0) {

                        $transaction = new \App\Models\Transaction;
                        $transaction->invoice_id = $invoice->id;
                        $transaction->amount = $invoice->amount;
                        $transaction->patient_id = $invoice->patient_id;

                        $user = $invoice->patient->user;

                	// convert for stripe
                	$amount = $invoice->amount * 100;

                	$data = [
                		'customer' => $user->stripe_id,
                		'amount' => $amount,
                		'currency' => 'usd',
                		'description' => $invoice->description
                	];

                	try {

                		$charge = \Stripe\Charge::create($data);

                                // success!
                                if ($charge->paid) {

                                        $transaction->transaction_id = $charge->id;
                                        $transaction->transaction_date = date('Y-m-d H:i:s');
                                        $transaction->success = true;

                                        $invoice->paid_on = $transaction->transaction_date;
                                        $invoice->transaction_id = $transaction->id;
                                        $invoice->card_brand = $charge->source->brand;
                                        $invoice->card_last_four = $charge->source->last4;
                                        $invoice->status = Invoice::PAID_STATUS;

                                        // reset any billing error flags
                                        $user->billing_error = 0;

                                        // fire an event
                                        event(new ChargeSucceeded($invoice, $transaction));

                                // not success, but would this ever happen?
                                } else {

                                        $transaction->transaction = $charge->id;
                                        $transaction->gateway = 'stripe';
                                        $transaction->transaction_date = date('Y-m-d H:i:s');
                                        $transaction->success = false;

                                        $user->billing_error++;

                                        // fire an event
                                        event(new ChargeFailed($invoice, null, $transaction));
                                }
                		
                        // exception/failure
                	} catch (\Exception $e) {

                                $transaction->gateway = 'stripe';
                                $transaction->response_text = $e->getMessage();
                                $transaction->transaction_date = date('Y-m-d H:i:s');
                                $transaction->success = false;
                		
                                // report it to engineering because it was an exception
                		ops_error('Stripe Exception','Could not charge for Invoice ' . $invoice->id . ': ' . $e->getMessage());

                                $user->billing_error++;

                                // fire an event
                                event(new ChargeFailed($invoice, $e));
                	}

                        // if we had a billing error, try again in 24 hours
                        // but only try for a maximum of 3 times
                        if ($user->billing_error > 0 && $user->billing_error < 3) {
                                $job = (new ChargePatientForInvoice($invoice))->delay(Carbon::now()->addHours(24));
                                dispatch($job);
                        }

                        $transaction->save();
                        $user->save();

                // zero balance just gets a pass, with no transaction
                } else {

                        $invoice->paid_on = date('Y-m-d H:i:s');
                }
                
                $invoice->save();
	}
}