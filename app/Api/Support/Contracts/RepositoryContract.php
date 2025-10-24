<?php

namespace App\Api\Support\Contracts;

use Illuminate\Database\Eloquent\Builder;

/**
 * @template T of \Illuminate\Database\Eloquent\Model
 */
interface RepositoryContract
{
    /**
     * Retorna a Query Builder do Repositorio
     *
     * @return Builder<T>
     */
    public function query();
}

