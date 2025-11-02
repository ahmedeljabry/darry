<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class State extends Model
{
    use HasFactory;

    protected $fillable = ['governorate_id','name'];

    public function governorate(): BelongsTo
    {
        return $this->belongsTo(Governorate::class);
    }
}

