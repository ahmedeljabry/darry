<?php
declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Support\Arr;

class Place
{
    public static function countries(): array
    {
        return config('places.countries', []);
    }

    public static function governorates(?string $country = null): array
    {
        $governorates = config('places.governorates', []);
        if ($country && isset($governorates[$country])) {
            return $governorates[$country];
        }

        $first = Arr::first($governorates, fn ($value) => is_array($value));
        return is_array($first) ? $first : [];
    }

    public static function states(?string $country = null): array
    {
        $states = config('places.states', []);
        if ($country && isset($states[$country])) {
            return $states[$country];
        }

        $first = Arr::first($states, fn ($value) => is_array($value));
        return is_array($first) ? $first : [];
    }
}

