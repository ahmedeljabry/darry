<?php
declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToProperty;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    use HasFactory;
    use BelongsToProperty;

    protected $fillable = [
        'property_id',
        'full_name', 'tenant_type', 'national_id_or_cr', 'work_or_study_place', 'email', 'phone', 'phone2', 'address',
    ];

    public function relatives(): HasMany
    {
        return $this->hasMany(TenantRelative::class);
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }
}
