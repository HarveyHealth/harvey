<?php

namespace App\Http\Controllers\API\V1;

use App\Models\{Invoice};
use App\Transformers\V1\{InvoiceTransformer, InvoiceItemTransformer};
use App\Lib\Validation\StrictValidator;
use Illuminate\Http\Request;
use Storage;

class InvoicesController extends BaseAPIController
{
    protected $resource_name = 'invoices';

    /**
     * InvoicesController constructor.
     * @param InvoiceTransformer $transformer
     */
    public function __construct(InvoiceTransformer $transformer)
    {
        parent::__construct();
        $this->transformer = $transformer;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        if (currentUser()->isAdmin()) {
            $builder = Invoice::make();
        } elseif(currentUser()->isPatient()) {
            $builder = Invoice::where('patient_id', currentUser()->patient->id );
        }
        else {
            return $this->respondNotAuthorized('You are not authorized to access this endpoint.');
        }

        return $this->baseTransformBuilder($builder, request('include'), $this->transformer, request('per_page'))->respond();
    }

    /**
     * @param Invoice $invoice
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOne(Invoice $invoice)
    {
        if (currentUser()->cant('view', $invoice)) {
            return $this->respondNotAuthorized("You do not have access to view the invoice with id {$invoice->id}.");
        }

        return $this->baseTransformItem($invoice, request('include'))->respond();
    }


    public function getItems(Request $request, Invoice $invoice)
    {
        if (currentUser()->cant('view', $invoice)) {
            return $this->respondNotAuthorized("You do not have access to view the invoice with id {$invoice->id}.");
        }

        if (!$invoice->items) {
            return response()->json([], ResponseCode::HTTP_SERVICE_UNAVAILABLE);
        }

        $this->resource_name = "invoice_items";

        return $this->baseTransformCollection($invoice->items, null, new InvoiceItemTransformer)->respond();
    }
}
