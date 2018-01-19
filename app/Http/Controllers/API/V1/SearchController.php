<?php

namespace App\Http\Controllers\API\V1;

use App\Lib\Validation\StrictValidator;
use App\Models\{Attachment, LabTest, LabTestResult, Prescription, SoapNote};
use App\Transformers\V1\SearchResultTransformer;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Exception, ResponseCode, Storage;

class SearchController extends BaseAPIController
{
    /**
     * LabOrdersController constructor.
     * @param LabOrderTransformer $transformer
     */
    public function __construct(SearchResultTransformer $transformer)
    {
        parent::__construct();
        $this->transformer = $transformer;
    }

    public function search(Request $request)
    {
        // perform validation
        $validator = StrictValidator::check($request->all(), [
            'term' => 'required|string',
        ]);

        // get the search string
        $term = request('term');

        // search in multiple models
        $soap_notes = SoapNote::whereIn('id', SoapNote::search($term)->get()->pluck('id'));
        $attachments = Attachment::search($term);
        $prescriptions = Prescription::search($term);
        $labresults = LabTestResult::whereIn('id', LabTestResult::search($term)->get()->pluck('id'));

        // limit results to current user
        if (currentUser()->isNotAdmin()) {
            if (currentUser()->isPatient()) {
                $soap_notes->where('patient_id', currentUser()->patient->id)->select(['id', 'patient_id', 'created_by_user_id', 'plan', 'created_at', 'updated_at', 'deleted_at']);
                $attachments->where('patient_id', currentUser()->patient->id);
                $prescriptions->where('patient_id', currentUser()->patient->id);
                $labresults->whereIn('lab_test_id', LabTest::whereHas('labOrder', function ($query) {
                    $query->where('patient_id', currentUser()->patient->id);
                })->get()->pluck('id'));
            } elseif (currentUser()->isPractitioner()) {
                $soap_notes->where('created_by_user_id', currentUser()->practitioner->user->id);
                $attachments->where('created_by_user_id', currentUser()->practitioner->user->id);
                $prescriptions->where('created_by_user_id', currentUser()->practitioner->user->id);
                $labresults->whereIn('lab_test_id', LabTest::whereHas('labOrder', function ($query) {
                    $query->where('practitioner_id', currentUser()->practitioner->id);
                })->get()->pluck('id'));
            } else {
                throw new Exception('Unable to filter search results for "' . ucfirst(currentUser()->type) . '" User type.');
            }
        }

        $collection = collect($soap_notes->get())
            ->merge(collect($attachments->get()))
            ->merge(collect($prescriptions->get()))
            ->merge(collect($labresults->get()))
            ->sortByDesc('created_at');

        // add support for pagination
<<<<<<< HEAD
        if (is_numeric(request('per_page'))){
=======
        if (is_numeric(request('per_page'))) {
>>>>>>> e7df8935b5ae16fd121a3342f33c66299d1525f1
            $perPage = request('per_page');

            $currentPage = LengthAwarePaginator::resolveCurrentPage('page');
            $currentPageItems = $collection->slice(($currentPage - 1) * $perPage, $perPage);

            parse_str(request()->getQueryString(), $query);
            unset($query['page']);
            $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
                $currentPageItems,
                $collection->count(),
                $perPage,
                $currentPage,
                [
                    'pageName' => 'page',
                    'path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath(),
                    'query' => $query,
                ]
            );
            $paginator = new IlluminatePaginatorAdapter($paginator);
        } else {
            $paginator = null;
            $currentPageItems = $collection;
        }

        // return response
        return $this->baseTransformCollection($currentPageItems, null, null, $paginator)->respond();
    }
}
