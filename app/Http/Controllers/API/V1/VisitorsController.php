<?php

namespace App\Http\Controllers\API\V1;

use App\Lib\{TimeInterval, TransactionalEmail, Validation\StrictValidator};
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Redis, ResponseCode;

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
            'template' => ['required', Rule::in(['pdf'])],
        ]);

        $email = request('to');
        $template = request('template');
        $redisKey = "visitor.{$template}-email-sent-to-{$email}";

        if (Redis::get($redisKey)) {
            $message = 'Harvey already mailed that email address today, please give a try tomorrow. Thanks!.';
            return $this->respondWithError($message, 'Too many Requests.', ResponseCode::HTTP_TOO_MANY_REQUESTS);
        }

        dispatch(TransactionalEmail::createJob($email, "visitor.{$template}", []));

        Redis::set($redisKey, true);
        Redis::expire($redisKey, TimeInterval::day()->toSeconds());

        return response()->json(['status' => 'Email sent.'], ResponseCode::HTTP_ACCEPTED);
    }
}
