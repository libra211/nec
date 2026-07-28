<?php

namespace App\Support;

class InputSanitizer
{
    public static function clean(string $value): string
    {
        $value = trim($value);
        $value = stripslashes($value);
        $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');

        return $value;
    }

    public static function cleanArray(array $data): array
    {
        return array_map(function ($value) {
            return is_string($value) ? self::clean($value) : $value;
        }, $data);
    }
}
