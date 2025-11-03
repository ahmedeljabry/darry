<?php
declare(strict_types=1);

namespace App\Support;

use App\Models\Property;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

final class PropertyAccess
{
    public static function currentId(): ?int
    {
        return Auth::user()?->property_id;
    }

    public static function applyToQuery(Builder $builder, string $column = 'property_id'): Builder
    {
        $propertyId = self::currentId();
        if ($propertyId) {
            $builder->where($builder->qualifyColumn($column), $propertyId);
        }

        return $builder;
    }

    public static function ensureProperty(Property $property): void
    {
        $propertyId = self::currentId();
        if ($propertyId && $property->id !== $propertyId) {
            abort(403);
        }
    }
}

