<?php
declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToProperty;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Floor extends Model
{
    use HasFactory;
    use BelongsToProperty;

    protected $fillable = [
        'property_id',
        'name_ar',
        'description_ar',
        'description_en',
        'sort_order',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }
}
