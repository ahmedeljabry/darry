<?php
declare(strict_types=1);

namespace App\Models;

use App\Domain\Enums\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_no','property_id','unit_id','tenant_id','start_date','duration_months','end_date','payment_method','payment_day','rent_amount'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'payment_method' => PaymentMethod::class,
        'rent_amount' => 'decimal:2',
    ];

    public function property(): BelongsTo { return $this->belongsTo(Property::class); }
    public function unit(): BelongsTo { return $this->belongsTo(Unit::class); }
    public function tenant(): BelongsTo { return $this->belongsTo(Tenant::class); }
}


