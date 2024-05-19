<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiKey extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'name',
    ];

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }

    public function logs()
    {
        return $this->hasMany(Log::class);
    }
}
