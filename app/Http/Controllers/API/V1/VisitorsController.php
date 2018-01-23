<?php

namespace App\Http\Controllers\API\V1;

use App\Lib\Validation\StrictValidator;
use App\Lib\{TimeInterval, TransactionalEmail, ZipCodeValidator};
use App\Models\LabTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Validation\Rule;
use ResponseCode;

class VisitorsController extends BaseAPIController
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendEmail(Request $request)
    {
        $inputData = $request->all();

        $validator = StrictValidator::check($inputData, [
            'to' => 'required|email',
            'template' => ['required', Rule::in(['subscribe'])],
        ]);

        $email = request('to');
        $template = request('template');
        $redisKey = "visitor.{$template}-email-sent-to-{$email}";

        if (Redis::get($redisKey)) {
            $message = 'Harvey already registered that email address today, please give a try tomorrow. Thanks!.';
            return $this->respondWithError($message, 'Too many Requests.', ResponseCode::HTTP_TOO_MANY_REQUESTS);
        }

        dispatch(TransactionalEmail::createJob($email, "visitor.{$template}", ['to' => $email]));

        Redis::set($redisKey, true);
        Redis::expire($redisKey, TimeInterval::day()->toSeconds());

        return response()->json(['status' => 'Email sent.'], ResponseCode::HTTP_ACCEPTED);
    }


    public function getHarveyAvailability(Request $request)
    {
        $states_collection = ZipCodeValidator::getStatesCollection();

        $consultations_serviceable_filter = function ($i) { return app(ZipCodeValidator::class)->isServiceable($i); };
        $consultations_serviceable_states = $states_collection->filter($consultations_serviceable_filter)->values();

        $availability = [
            'data' => [
                'consultations' => [
                    'not_serviceable' => $states_collection->diff($consultations_serviceable_states)->values(),
                    'serviceable' => $consultations_serviceable_states,
                ],
                'supplements' => [
                    'not_serviceable' => [],
                    'serviceable' => $states_collection,
                ],
                'lab_tests' => [
                    'not_serviceable' => LabTest::getUnserviceableStatesCollection(),
                    'require_consultation' => LabTest::getRequireConsultationStatesCollection(),
                    'serviceable' => LabTest::getServiceableStatesCollection(),
                ],
            ],
        ];

        return response()->json($availability, ResponseCode::HTTP_OK);
    }
}
