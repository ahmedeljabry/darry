<?php
declare(strict_types=1);

namespace App\Models;

use App\Domain\Enums\PaymentMethod;
use App\Models\Concerns\BelongsToProperty;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractPayment extends Model
{
    use HasFactory;
    use BelongsToProperty;

    protected $fillable = [
        'property_id','unit_id','tenant_id','period_month','period_year','amount_due','amount_paid','due_date','paid_at','method','details'
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_at' => 'datetime',
        'method' => PaymentMethod::class,
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

}
