<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $table = 'logs';

    protected $fillable = [
        'time',
        'ip',
        'user_id',
        'api_key_id',
        'action'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function apiKey()
    {
        return $this->belongsTo(ApiKey::class, 'api_key_id');
    }

    public static function logUserActivity($action): bool
    {
        return true;

    }

    public static function logApiActivity($action, $apiKey): bool
    {
        return true;
    }
}
