<?php
declare(strict_types=1);

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait BelongsToProperty
{
    protected static function bootBelongsToProperty(): void
    {
        static::addGlobalScope('property', function (Builder $builder) {
            $user = Auth::user();
            if (!$user || !$user->property_id) {
                return;
            }

            $builder->where(
                $builder->getModel()->qualifyColumn('property_id'),
                $user->property_id
            );
        });

        static::creating(function ($model) {
            $user = Auth::user();
            if ($user && $user->property_id && empty($model->property_id)) {
                $model->property_id = $user->property_id;
            }
        });
    }

    public function scopeForProperty(Builder $builder, ?int $propertyId): Builder
    {
        if ($propertyId) {
            $builder->where(
                $builder->getModel()->qualifyColumn('property_id'),
                $propertyId
            );
        }

        return $builder;
    }
}

