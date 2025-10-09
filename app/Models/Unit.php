<?php
declare(strict_types=1);

namespace App\Models;

use App\Domain\Enums\RentType;
use App\Domain\Enums\UnitStatus;
use App\Domain\Enums\UnitType;
use App\Domain\Enums\UnitCategory;
use App\Domain\Enums\UnitOccupancyStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id','property_id','name','unit_type','capacity','rooms','toilets','category','rent_type','rent_amount','electricity_acc','water_acc','status','occupancy_status',
    ];

    protected $casts = [
        'unit_type' => UnitType::class,
        'rent_type' => RentType::class,
        'status' => UnitStatus::class,
        'category' => UnitCategory::class,
        'occupancy_status' => UnitOccupancyStatus::class,
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }
}

