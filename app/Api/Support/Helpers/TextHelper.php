<?php

namespace App\Api\Support\Helpers;

class TextHelper
{
    /**
     * Remove caracteres especiais mantendo apenas letras e números
     */
    public static function onlyAlphanumeric(?string $text): ?string
    {
        if ($text === null) {
            return null;
        }

        return preg_replace('/[^A-Za-z0-9]/', '', $text);
    }

    /**
     * Remove todos caracteres não numéricos
     */
    public static function onlyNumbers(?string $text): ?string
    {
        if ($text === null) {
            return null;
        }

        return preg_replace('/[^0-9]/', '', $text);
    }

    /**
     * Limpa e formata texto removendo espaços extras
     */
    public static function clean(?string $text): ?string
    {
        if ($text === null) {
            return null;
        }

        return trim(preg_replace('/\s+/', ' ', $text));
    }

    /**
     * Converte texto para slug
     */
    public static function slugify(?string $text): ?string
    {
        if ($text === null) {
            return null;
        }

        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9-]/', '-', $text);
        $text = preg_replace('/-+/', '-', $text);

        return trim($text, '-');
    }
}

