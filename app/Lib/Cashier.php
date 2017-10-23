<?php

namespace App\Lib;

use App\Events\{ChargeFailed, ChargeSucceeded};
use App\Http\Traits\Invoiceable;
use App\Jobs\ChargePatientForInvoice;
use App\Models\{Invoice, Transaction};
use Stripe\Charge;
use Carbon, Exception;

class Cashier
{
    public static function getOrCreateInvoice($invoiceable)
    {
        if (!in_array(Invoiceable::class, class_uses($invoiceable))) {
            throw new Exception('Invalid class ' . get_class($invoiceable) . '. Does not use invoiceable trait.');
        }

        if (!$invoice = $invoiceable->invoice) {
            $invoice = Invoice::newInvoiceWithData($invoiceable->dataForInvoice());
            $invoiceable = $invoiceable->fresh();
            $invoiceable->invoice_id = $invoice->id;
            $invoiceable->unsetEventDispatcher();
            $invoiceable->save();
        }

        return $invoice->fresh();
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

                    $user->billing_error = 0;

                    event(new ChargeSucceeded($invoice, $transaction));
                } else {
                    $transaction->gateway = 'stripe';
                    $transaction->response_code = $charge->failure_code;
                    $transaction->response_text = $charge->failure_message;
                    $transaction->success = false;
                    $transaction->transaction = $charge->id;
                    $transaction->transaction_date = Carbon::now();

                    $user->billing_error++;

                    event(new ChargeFailed($invoice, 'Charge Failed!', $transaction));
                }
            } catch (Exception $e) {
                $transaction->gateway = 'stripe';
                $transaction->response_code = $e->getCode();
                $transaction->response_text = $e->getMessage();
                $transaction->transaction_date = Carbon::now();
                $transaction->success = false;

                $invoice->card_brand = $user->card_brand;
                $invoice->card_last_four = $user->card_last_four;

                $user->billing_error++;

                event(new ChargeFailed($invoice, $e->getMessage(), $transaction));
            }

            $transaction->save();
            $user->save();

            if ($user->billing_error > 0 && $user->billing_error < 3) {
                dispatch((new ChargePatientForInvoice($invoice))->delay(Carbon::now()->addHours(24)));
                return false;
            }
        } else {
            $invoice->paid_on = Carbon::now();
        }

        return $invoice->save();
    }
}
