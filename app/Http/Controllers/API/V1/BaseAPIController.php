<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Crell\ApiProblem\ApiProblem;
use League\Fractal\Serializer\JsonApiSerializer;
use Symfony\Component\HttpFoundation\Response as ResponseCode;

class BaseAPIController extends Controller
{
    /**
     * @var int
     */
    protected $status_code = ResponseCode::HTTP_OK;
    protected $resource_name;

    /**
     * @var JsonApiSerializer
     */
    protected $serializer;
    protected $transformer;

    /**
     * BaseAPIController constructor.
     */
    public function __construct()
    {
        $this->serializer = new JsonApiSerializer(config('app.url') . '/api/v1');
    }

    /**
     * @param $code
     * @return $this
     */
    public function setStatusCode($code)
    {
        $this->status_code = $code;
        return $this;
    }

    /**
     * @return int
     */
    protected function getStatusCode()
    {
        return $this->status_code;
    }


    protected function respondWithError(ApiProblem $apiproblem)
    {
        return response()->apiproblem($apiproblem->asArray(), $this->getStatusCode());
    }

    public function respondBadRequest(ApiProblem $problem)
    {
        $problem->setTitle("Bad Request.");
        return $this->setStatusCode(ResponseCode::HTTP_BAD_REQUEST)
            ->respondWithError($problem);
    }

    public function respondNotAuthorized(ApiProblem $problem)
    {
        $problem->setTitle("Unauthorized Access.");
        return $this->setStatusCode(ResponseCode::HTTP_UNAUTHORIZED)
                ->respondWithError($problem);
    }

    public function respondNotFound(ApiProblem $problem)
    {
        $problem->setTitle("Not Found.");
        return $this->setStatusCode(ResponseCode::HTTP_NOT_FOUND)
            ->respondWithError($problem);
    }

    public function respondUnprocessable(ApiProblem $problem)
    {
        $problem->setTitle("Unprocessable Entity.");
        return $this->setStatusCode(ResponseCode::HTTP_UNPROCESSABLE_ENTITY)
            ->respondWithError($problem);
    }

    public function transformedResponse($item, $transformer = null) {

        if (is_a($item, Illuminate\Database\Eloquent\Collection::class)) {
            return fractal()->collection($item)
                ->withResourceName($this->resource_name)
                ->transformWith($transformer ?? $this->transformer)
                ->serializeWith($this->serializer)
                ->respond();
        } else {
            return fractal()->item($item)
                ->withResourceName($this->resource_name)
                ->transformWith($transformer ?? $this->transformer)
                ->serializeWith($this->serializer)
                ->respond();
        }
    }
}
