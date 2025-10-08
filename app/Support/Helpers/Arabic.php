<?php
declare(strict_types=1);

namespace App\Support\Helpers;

final class Arabic
{
    private const WESTERN = ['0','1','2','3','4','5','6','7','8','9'];
    private const ARABIC  = ['٠','١','٢','٣','٤','٥','٦','٧','٨','٩'];

    public static function digits(string $value): string
    {
        if (! config('arabic.use_arabic_indic_digits')) {
            return $value;
        }
        return str_replace(self::WESTERN, self::ARABIC, $value);
    }
}

