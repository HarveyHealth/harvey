<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response as ResponseCode;

class BaseAPIController extends Controller
{
    protected $status_code = ResponseCode::HTTP_OK;

    public function setStatusCode($code)
    {
        $this->status_code = $code;
        return $this;
    }
    
    protected function getStatusCode()
    {
        return $this->status_code;
    }
    
    protected function respond($data, $meta = null)
    {
        $output = ['data' => $data, 'meta' => $meta];
        
        return response()->json($output, ResponseCode::HTTP_OK);
    }
    
    protected function respondWithError($message)
    {
        return response()->json([
            'error' => [
                'message' => $message
            ]
        ], $this->getStatusCode());
    }
    
    public function respondBadRequest($message = 'Bad Request')
    {
        return $this->setStatusCode(ResponseCode::HTTP_BAD_REQUEST)->respondWithError($message);
    }
    
    public function respondNotAuthorized($message = 'Unauthorized Access')
    {
        return $this->setStatusCode(ResponseCode::HTTP_UNAUTHORIZED)->respondWithError($message);
    }
    
    public function respondNotFound($message = 'Not Found')
    {
        return $this->setStatusCode(ResponseCode::HTTP_NOT_FOUND)->respondWithError($message);
    }
    
    public function respondUnprocessable($message = 'Unprocessable Entity')
    {
        return $this->setStatusCode(ResponseCode::HTTP_UNPROCESSABLE_ENTITY)->respondWithError($message);
    }
}
