<?php

namespace App\Http\Controllers\API\alpha;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BaseAPIController extends Controller {

    const CODE_INVALID_ARGS = 'INVALID ARGUMENTS';
    const CODE_NOT_FOUND = 'NOT FOUND';
    const CODE_INTERNAL_ERROR = 'INTERNAL ERROR';
    const CODE_UNAUTHORIZED = 'UNAUTHORIZED';
    const CODE_FORBIDDEN = 'FORBIDDEN';

    protected $status_code = 200;

    public function __construct() {
        // $this->middleware('auth:api');
    }

    protected function statusCode() {
        return $this->status_code;
    }

    protected function setStatusCode($code) {
        $this->status_code = $code;
        return $this;
    }

    protected function respondWithItem($item, $transformer = null)
    {
        // if the transformer is unspecified, we
        // need to guess
        if (empty($transformer)) {
            $class_transformer = class_basename($item) . 'Transformer';

            // see if a transformer exists
            if (class_exists($class_transformer)) {
                $transformer = new $class_transformer;
            } else {
                $transformer = new BaseTransformer;
            }
        }

        $resource = new Item($item, $transformer);

        return $this->respondWithArray($root_scope->toArray());
    }

    protected function respondWithCollection($item, $transformer = null)
    {
        // if the transformer is unspecified, we
        // need to guess
        if (empty($transformer)) {
            $class = class_basename($item[0]);

            // see if a transformer exists
            if (class_exists($class))
                $transformer = new $class;
        }

        $resource = new Collection($item, $transformer);

        $root_scope = $this->fractal->createData($resource);

        return $this->respondWithArray($root_scope->toArray());
    }

    protected function respondWithArray(array $array, array $headers = [])
    {
        return response()->json($array, $this->status_code, $headers);
    }

    protected function respondWithError($message, $error_code)
    {

        if ($this->status_code === 200) {
            trigger_error(
                "You better have a really good reason for erroring on a 200...",
                E_USER_WARNING
            );
        }

        return $this->respondWithArray([
            'error' => [
                'code' => $error_code,
                'http_code' => $this->status_code,
                'message' => $message
            ]
        ]);
    }

    protected function errorForbidden($message = 'Forbidden')
    {
        return $this->setStatusCode(403)
                    ->respondWithError($message, self::CODE_INTERNAL_ERROR);
    }

    protected function errorInternalError($message = 'Internal Error')
    {
        return $this->setStatusCode(500)
                    ->respondWithError($message, self::CODE_INTERNAL_ERROR);
    }

    protected function errorNotFound($message = 'Resource Not Found')
    {
        return $this->setStatusCode(404)
                    ->respondWithError($message, self::CODE_NOT_FOUND);
    }

    protected function errorUnauthorized($message = 'Unauthorized')
    {
        return $this->setStatusCode(401)
                    ->respondWithError($message, self::CODE_UNAUTHORIZED);
    }

    protected function errorInvalidArgs($message = 'Invalid Arguments')
    {
        return $this->setStatusCode(400)
                    ->respondWithError($message, self::CODE_INVALID_ARGS);
    }


}
