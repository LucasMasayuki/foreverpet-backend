<?php

namespace App\Api\Support\Repository;

use Exception;
use Illuminate\Container\Container as Application;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Model;

/**
 * @template T of Model
 */
abstract class BaseRepository
{
    protected Application $app;

    /**
     * @throws Exception
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @var T
     */
    protected Model $model;

    /**
     * Configure the Model
     *
     * @return class-string<T>
     */
    abstract public function model();

    /**
     * @return T
     *
     * @throws BindingResolutionException
     * @throws Exception
     */
    public function makeModel()
    {
        $model = $this->app->make($this->model());

        if (! $model instanceof Model) {
            throw new Exception("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $this->model = $model;
    }
}

