<?php
declare(strict_types=1);

namespace App\Helpers;

use App\Models\Country;
use App\Models\Governorate;
use App\Models\State;

class Place
{
    public static function countries(): array
    {
        return Country::query()->orderBy('name')->pluck('name', 'id')->toArray();
    }

    public static function governorates(?int $countryId = null): array
    {
        if (!$countryId) {
            return [];
        }

        return Governorate::query()
            ->where('country_id', $countryId)
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }

    public static function states(?int $governorateId = null): array
    {
        if (!$governorateId) {
            return [];
        }

        return State::query()
            ->where('governorate_id', $governorateId)
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }
}
