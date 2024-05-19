<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadComment extends Model
{
    protected $fillable = ['user_id', 'lead_id', 'body'];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
