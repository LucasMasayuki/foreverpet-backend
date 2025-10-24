<?php

namespace App\Api\Support\Data;

class Money
{
    /**
     * Converte valor do banco (centavos) para currency (formato com decimais)
     */
    public static function fromValueToCurrency(?int $value): ?float
    {
        if ($value === null) {
            return null;
        }

        return $value / 100;
    }

    /**
     * Converte currency (formato com decimais) para valor do banco (centavos)
     */
    public static function fromCurrencyToValue(?float $currency): ?int
    {
        if ($currency === null) {
            return null;
        }

        return (int) round($currency * 100);
    }

    /**
     * Formata valor para exibição em moeda brasileira
     */
    public static function format(?float $value): ?string
    {
        if ($value === null) {
            return null;
        }

        return 'R$ ' . number_format($value, 2, ',', '.');
    }
}

