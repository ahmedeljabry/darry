<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['key','value'];

    public static function get(string $key, mixed $default = null): mixed
    {
        $row = static::query()->where('key', $key)->first();
        if (!$row) return $default;
        $val = $row->value;
        $json = json_decode($val, true);
        return json_last_error() === JSON_ERROR_NONE ? $json : $val;
    }

    public static function set(string $key, mixed $value): void
    {
        $val = is_array($value) ? json_encode($value) : (string) $value;
        static::query()->updateOrCreate(['key' => $key], ['value' => $val]);
    }
}


