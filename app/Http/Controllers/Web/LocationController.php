<?php
declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Governorate;
use Illuminate\Http\JsonResponse;

class LocationController extends Controller
{
    public function governorates(int $countryId): JsonResponse
    {
        $country = Country::findOrFail($countryId);
        $items = $country->governorates()->orderBy('name')->get(['id','name']);

        return response()->json([
            'items' => $items,
        ]);
    }

    public function states(int $governorateId): JsonResponse
    {
        $governorate = Governorate::findOrFail($governorateId);
        $items = $governorate->states()->orderBy('name')->get(['id','name']);

        return response()->json([
            'items' => $items,
        ]);
    }
}

