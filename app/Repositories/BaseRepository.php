<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Container\Container as App;


class BaseRepository {

    /**
     * @var App
     */
    private $app;

    /**
     * @var
     */
    public $model;

    /**
     * @param App $app
     */
    public function __construct(App $app) {
        $this->app = $app;
        $this->makeModel();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function makeModel() {
        $model = $this->app->make($this->model);

        if (!$model instanceof Model)
            throw new \Exception("Class {$this->model} must be an instance of Illuminate\\Database\\Eloquent\\Model");

        return $this->model = $model->newQuery();
    }

    /**
     * Pass function calls down to the model.
     * @return mixed
     */
    public function __call($method, $args)
    {
        return call_user_func_array(array($this->model, $method), $args);
    }
}
