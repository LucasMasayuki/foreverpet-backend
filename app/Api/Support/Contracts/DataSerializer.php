<?php

namespace App\Api\Support\Contracts;

interface DataSerializer
{
    /**
     * Converte os dados para array no formato do modelo
     */
    public function toArrayModel(): array;
}

