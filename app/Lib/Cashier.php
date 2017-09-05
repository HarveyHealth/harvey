<?php

namespace App\Lib;

use App\Events\{ChargeFailed, ChargeSucceeded};
use App\Http\Traits\Invoiceable;
use App\Jobs\ChargePatientForInvoice;
use App\Models\{Invoice, Transaction};
use Carbon, Exception;
use Stripe\Charge;

class Cashier
{
    public static function getOrCreateInvoice($invoiceable)
    {
        if (!in_array(Invoiceable::class, class_uses($invoiceable))) {
            throw new Exception('Invalid class ' . get_class($invoiceable) . '. Does not use invoiceable trait. ');
        }

        if (!$invoice = $invoiceable->invoice) {
            $invoice = Invoice::newInvoiceWithData($invoiceable->dataForInvoice());
            $invoiceable->invoice_id = $invoice->id;
            $invoiceable->save();
        }

        return $invoice;
    }

    public static function chargePatientForInvoice(Invoice $invoice)
    {
        // make sure invoice hasn't already been processed
        if ($invoice->isNotOutstanding()) {
            return false;
        }

        // calculate the subtotals, if needed
        $invoice->calculateTotals();

        // no need to charge if there's no balance
        if ($invoice->amount > 0) {
            $transaction = new Transaction;
            $transaction->invoice_id = $invoice->id;
            $transaction->amount = $invoice->amount;
            $transaction->patient_id = $invoice->patient_id;
            $transaction->save();

            $user = $invoice->patient->user;

            // convert for stripe
            $amount = $invoice->amount * 100;

            $data = [
                'customer' => $user->stripe_id,
                'amount' => $amount,
                'currency' => 'usd',
                'description' => $invoice->description,
            ];

            try {
                $charge = Charge::create($data);

                // success!
                if ($charge->paid) {
                    $transaction->response_code = $charge->getLastResponse()->code;
                    $transaction->response_text = $charge->outcome->seller_message;
                    $transaction->success = true;
                    $transaction->transaction_date = Carbon::now();
                    $transaction->transaction_id = $charge->id;

                    $invoice->paid_on = $transaction->transaction_date;
                    $invoice->transaction_id = $transaction->id;
                    $invoice->card_brand = $charge->source->brand;
                    $invoice->card_last_four = $charge->source->last4;
                    $invoice->status = Invoice::PAID_STATUS;

                    // reset any billing error flags
                    $user->billing_error = 0;

                    event(new ChargeSucceeded($invoice, $transaction));

                // not success, but would this ever happen?
                } else {
                    $transaction->gateway = 'stripe';
                    $transaction->response_code = $charge->failure_code;
                    $transaction->response_text = $charge->failure_message;
                    $transaction->success = false;
                    $transaction->transaction = $charge->id;
                    $transaction->transaction_date = Carbon::now();

                    $user->billing_error++;

                    event(new ChargeFailed($invoice, null, $transaction));
                }
            } catch (Exception $e) {
                $transaction->gateway = 'stripe';
                $transaction->response_code = $e->getCode();
                $transaction->response_text = $e->getMessage();
                $transaction->transaction_date = Carbon::now();
                $transaction->success = false;

                $user->billing_error++;

                ops_error('Stripe Exception', "Could not charge User #{$user->id} for Invoice #{$invoice->id}. _Error:_ '{$e->getMessage()}'");

                event(new ChargeFailed($invoice, $e));
            }

            $transaction->save();
            $user->save();

            // if we had a billing error, try again in 24 hours
            // but only try for a maximum of 3 times
            if ($user->billing_error > 0 && $user->billing_error <= 3) {
                $job = (new ChargePatientForInvoice($invoice))->delay(Carbon::now()->addHours(24));
                dispatch($job);
                return false;
            }
        } else {
            $invoice->paid_on = Carbon::now();
        }

        return $invoice->save();
    }
}
