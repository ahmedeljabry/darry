<?php
declare(strict_types=1);

namespace App\Models;

use App\Domain\Enums\PropertyUseType;
use App\Models\Floor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','country','state','governorate','city','area_sqm','use_type','thumbnail','images','coordinates'
    ];

    protected $casts = [
        'use_type' => PropertyUseType::class,
        'images' => 'array',
    ];

    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([$this->country, $this->governorate, $this->state, $this->city]);
        return trim(implode(' - ', $parts));
    }

    public function facilities(): BelongsToMany
    {
        return $this->belongsToMany(Facility::class, 'facility_property');
    }

    public function units(): HasMany
    {
        return $this->hasMany(Unit::class);
    }

    public function floors(): HasMany
    {
        return $this->hasMany(Floor::class)->orderBy('sort_order')->orderBy('id');
    }
}
