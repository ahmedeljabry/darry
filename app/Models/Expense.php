<?php
declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToProperty;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    use HasFactory;
    use BelongsToProperty;

    protected $fillable = ['property_id','unit_id','title','amount','date','receipt_no','category'];

    protected $casts = [
        'date' => 'date',
    ];

    public function property(): BelongsTo { return $this->belongsTo(Property::class); }
    public function unit(): BelongsTo { return $this->belongsTo(Unit::class); }
}

