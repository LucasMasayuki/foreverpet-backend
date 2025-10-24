<?php

namespace App\Api\Support\Traits;

trait DataUtilsTrait
{
    /**
     * Converte os dados para array no formato do modelo
     * Remove valores Optional e ajusta nomes de campos
     */
    public function toArrayModel(): array
    {
        $data = $this->toArray();

        // Remove campos Optional vazios
        foreach ($data as $key => $value) {
            if ($value === null || (is_object($value) && get_class($value) === 'Spatie\LaravelData\Optional')) {
                unset($data[$key]);
            }
        }

        return $data;
    }

    /**
     * Valida se um campo é obrigatório
     */
    protected function validateRequired(string $field, $value, string $message = null): void
    {
        if (empty($value) && $value !== 0 && $value !== false) {
            throw new \InvalidArgumentException(
                $message ?? "O campo {$field} é obrigatório"
            );
        }
    }

    /**
     * Valida se um valor está entre um range
     */
    protected function validateBetween(string $field, $value, $min, $max, string $message = null): void
    {
        if ($value < $min || $value > $max) {
            throw new \InvalidArgumentException(
                $message ?? "O campo {$field} deve estar entre {$min} e {$max}"
            );
        }
    }
}

