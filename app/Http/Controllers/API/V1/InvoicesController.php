<?php

namespace App\Http\Controllers\API\V1;

use App\Models\{Invoice};
use App\Transformers\V1\{InvoiceTransformer, InvoiceItemTransformer};
use App\Lib\Validation\StrictValidator;
use Illuminate\Http\Request;
use Storage;

class InvoicesController extends BaseAPIController
{
    protected $resource_name = 'invoice';

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
        if (currentUser()->isNotAdmin()) {
            return $this->respondNotAuthorized('You are not authorized to list invoices.');
        }

        $builder = Invoice::make();
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
        if (currentUser()->isNotAdmin()) {
            return $this->respondNotAuthorized("You do not have access to view the invoice with id {$invoice->id}.");
        }

        if (!$items = $invoice->items()) {
            return response()->json([], ResponseCode::HTTP_SERVICE_UNAVAILABLE);
        }

        $this->resource_name = "invoice_items";

        return $this->baseTransformCollection($items, null, new InvoiceItemTransformer)->respond();
    }
}
