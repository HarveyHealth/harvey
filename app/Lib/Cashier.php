<?php

namespace App\Lib;

use App\Models\Patient;
use App\Models\Invoice;

class Cashier
{
	public function generatePatientInvoiceForInvoiceable($invoiceable)
	{
		$invoice_data = $invoiceable->dataForInvoice();

        $invoice = Invoice::invoiceWithData($invoice_data);
        $invoice->patient_id = $invoiceable->patient_id;
        $invoice->calculateTotals();

        return $invoice;
	}

	public function chargePatientForInvoice(Invoice $invoice)
	{

	}
}