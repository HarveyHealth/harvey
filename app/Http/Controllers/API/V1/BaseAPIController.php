<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Crell\ApiProblem\ApiProblem;
use League\Fractal\Serializer\JsonApiSerializer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Spatie\Fractal\Fractal;
use \ResponseCode;

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


    protected function respondWithError($message, $title = 'Internal Server Error', $code = ResponseCode::HTTP_INTERNAL_SERVER_ERROR)
    {
        $this->setStatusCode($code);

        $problem = new ApiProblem();
        $problem->setTitle($title);
        $problem->setDetail($message);

        return response()->apiproblem($problem->asArray(), $this->getStatusCode());
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
    public function baseTransformItem($item, $include = null, $transformer = null)
    {
        return fractal()->item($item)
            ->parseIncludes($include)
            ->withResourceName($this->resource_name)
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
}
