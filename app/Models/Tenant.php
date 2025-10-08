<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name', 'tenant_type', 'national_id_or_cr', 'work_or_study_place', 'email', 'phone', 'phone2', 'address',
    ];

    public function relatives(): HasMany
    {
        return $this->hasMany(TenantRelative::class);
    }
}

