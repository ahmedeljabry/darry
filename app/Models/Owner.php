<?php
declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToProperty;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Owner extends Model
{
    use HasFactory;
    use BelongsToProperty;

    protected $fillable = [
        'property_id',
        'full_name',
        'id_or_cr',
        'email',
        'phone',
        'address',
        'owner_type',
        'status',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }
}
