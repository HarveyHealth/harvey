<?php
namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Transformers\V1\SearchResultTransformer;
use Exception, ResponseCode, Storage;
use App\Lib\Validation\StrictValidator;
use App\Models\{SoapNote, Attachment, Prescription, LabTestResult};

class SearchController extends BaseAPIController
{
    protected $resource_name = 'results';

    /**
     * LabOrdersController constructor.
     * @param LabOrderTransformer $transformer
     */
    public function __construct(SearchResultTransformer $transformer)
    {
        parent::__construct();
        $this->transformer = $transformer;
    }

    public function search(Request $request){
        $validator = StrictValidator::check($request->all(), [
            'term' => 'required|string',
        ]);

        $term = request('term');

        $results = collect(SoapNote::search($term)->get())
            ->merge(collect(Atachment::search($term)->get()))
            ->merge(collect(Prescription::search($term)->get()))
            ->merge(collect(LabTestResult::search($term)->get())
            ->sortByDesc('created_at');


        return $this->baseTransformCollection($results)->respond();
    }
}
