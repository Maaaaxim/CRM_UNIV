<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'lead_id', 'message', 'time'];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
}
