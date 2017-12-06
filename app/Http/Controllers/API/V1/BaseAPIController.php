<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Lib\HarveyApiSerializer;
use Crell\ApiProblem\ApiProblem;
use Illuminate\Validation\Validator;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use ResponseCode;
use League\Fractal\Resource\Collection;

class BaseAPIController extends Controller
{
    protected $status_code = ResponseCode::HTTP_OK;
    protected $resource_name;
    protected $apiProblem, $serializer, $transformer;

    /**
     * BaseAPIController constructor.
     */
    public function __construct()
    {
        $this->serializer = new HarveyApiSerializer(config('app.url') . '/api/v1');
        $this->apiProblem = new ApiProblem;
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


    protected function respondWithError($message, $title = 'Internal Server Error', $code = ResponseCode::HTTP_INTERNAL_SERVER_ERROR)
    {
        $this->setStatusCode($code);

        $this->apiProblem->setTitle($title);
        $this->apiProblem->setDetail($message);

        return response()->apiproblem($this->apiProblem->asArray(), $this->getStatusCode());
    }

    public function respondBadRequest($message)
    {
        return $this->respondWithError($message, 'Bad Request.', ResponseCode::HTTP_BAD_REQUEST);
    }

    public function respondNotAuthorized($message)
    {
        return $this->respondWithError($message, 'Unauthorized Access.', ResponseCode::HTTP_UNAUTHORIZED);
    }

    public function respondNotFound($message)
    {
        return $this->respondWithError($message, 'Not Found.', ResponseCode::HTTP_NOT_FOUND);
    }

    public function respondUnprocessable($message)
    {
        return $this->respondWithError($message, 'Unprocessable Entity.', ResponseCode::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @param      $item
     * @param      $include
     * @param null $transformer
     * @return Fractal
     */
    public function baseTransformItem($item, $include = null, $transformer = null, $resourceName = null)
    {
        return fractal()->item($item)
            ->parseIncludes($include)
            ->withResourceName($resourceName ?? $this->resource_name)
            ->transformWith($transformer ?? $this->transformer)
            ->serializeWith($this->serializer);
    }

    /**
     * @param      $collection
     * @param      $include
     * @param null $transformer
     * @param \League\Fractal\Pagination\IlluminatePaginatorAdapter $paginator
     * @return \Spatie\Fractal\Fractal
     */
    public function baseTransformCollection($collection, $include = null, $transformer = null, IlluminatePaginatorAdapter $paginationAdapter = null)
    {
        $output = fractal()->collection($collection);

        if ($paginationAdapter) {
            $output = $output->paginateWith($paginationAdapter);
        }

        return $output->parseIncludes($include)
            ->withResourceName($this->resource_name)
            ->transformWith($transformer ?? $this->transformer)
            ->serializeWith($this->serializer);
    }

    /**
     * @param  $builder
     * @param  $include
     * @param  $transformer
     * @param  $itemsPerPage
     * @return \Spatie\Fractal\Fractal
     */
    public function baseTransformBuilder($builder, $include = null, $transformer = null, $itemsPerPage = null)
    {
        if (is_numeric($itemsPerPage)) {
            $paginator = $builder->paginate((int) $itemsPerPage);
            $paginator->appends(array_diff_key(request()->all(), array_flip(['page'])));
            $collection = $paginator->items();
            $paginationAdapter =  new IlluminatePaginatorAdapter($paginator);
        } else {
            $collection = $builder->get();
            $paginationAdapter = null;
        }

        return $this->baseTransformCollection($collection, $include, $transformer, $paginationAdapter);
    }

    public function setApiProblemType(Validator $validator)
    {
        if (isset($validator->failed()['email']['Unique'])) {
            $type = 'email-in-use';
        } elseif (isset($validator->failed()['zip']['Serviceable'])) {
            $type = 'out-of-range';
        } else {
            $type = 'about:blank';
        }

        return $this->apiProblem->setType($type);
    }
}
