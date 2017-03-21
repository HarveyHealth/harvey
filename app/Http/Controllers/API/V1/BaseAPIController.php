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
    
    /**
     * @var JsonApiSerializer
     */
    protected $serializer;
    
    /**
     * @var ApiProblem
     */
    protected $problem;
    
    /**
     * BaseAPIController constructor.
     */
    public function __construct()
    {
        $this->serializer = new JsonApiSerializer(config('app.url') . '/api/v1');
        $this->problem = new ApiProblem(null, null);
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
    
    /**
     * @param $problem
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithError($problem)
    {
        return response()->json([
            'errors' => [
                $problem
            ]
        ], $this->getStatusCode());
    }
    
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondBadRequest()
    {
        $this->setProblemTitleIfMissing('Bad Request');
        return $this->setStatusCode(ResponseCode::HTTP_BAD_REQUEST)
                ->respondWithError($this->problem->asArray());
    }
    
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondNotAuthorized()
    {
        $this->setProblemTitleIfMissing('Unauthorized Access');
        return $this->setStatusCode(ResponseCode::HTTP_UNAUTHORIZED)
                ->respondWithError($this->problem->asArray());
    }
    
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondNotFound()
    {
        $this->setProblemTitleIfMissing('Not Found');
        return $this->setStatusCode(ResponseCode::HTTP_NOT_FOUND)
                ->respondWithError($this->problem->asArray());
    }
    
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondUnprocessable()
    {
        $this->setProblemTitleIfMissing('Unprocessable Entity');
        return $this->setStatusCode(ResponseCode::HTTP_UNPROCESSABLE_ENTITY)
                ->respondWithError($this->problem->asArray());
    }
    
    /**
     * @return bool
     */
    protected function problemTitleMissing()
    {
        return !$this->problem->getTitle();
    }
    
    /**
     * @param $title
     * @return $this
     */
    protected function setProblemTitleIfMissing($title)
    {
        if ($this->problemTitleMissing()) {
            $this->problem->setTitle($title);
        }
        return $this;
    }
}
