<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TenantRelative extends Model
{
    use HasFactory;

    protected $fillable = ['tenant_id','name','id_no','phone','kinship'];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}


