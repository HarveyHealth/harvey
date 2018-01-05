<?php

namespace App\Http\Controllers\API\V1;

use App\Models\{InvoiceItem};
use App\Transformers\V1\{InvoiceItemTransformer};
use App\Lib\Validation\StrictValidator;
use Illuminate\Http\Request;
use Storage;

class InvoiceItemsController extends BaseAPIController
{
    protected $resource_name = 'invoice_item';

    /**
     * InvoiceItemsController constructor.
     * @param InvoiceItemTransformer $transformer
     */
    public function __construct(InvoiceItemTransformer $transformer)
    {
        parent::__construct();
        $this->transformer = $transformer;
    }

    /**
     * @param InvoiceItem $invoice_item
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOne(InvoiceItem $invoice_item)
    {
        if (currentUser()->cant('view', $invoice_item)) {
            return $this->respondNotAuthorized("You do not have access to view the Invoice Item with id {$invoice_item->id}.");
        }

        return $this->baseTransformItem($invoice_item, request('include'))->respond();
    }
}
