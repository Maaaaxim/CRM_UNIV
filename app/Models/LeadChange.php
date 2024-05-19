<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadChange extends Model
{
    protected $fillable = ['change_type', 'changed_by', 'old_value', 'new_value', 'change_date'];

    public function user()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id', 'id');
    }


    public function oldUser()
    {
        return $this->belongsTo(User::class, 'old_value');
    }

    public function newUser()
    {
        return $this->belongsTo(User::class, 'new_value');
    }

    public function oldStatus()
    {
        return $this->belongsTo(Status::class, 'old_value');
    }

    public function newStatus()
    {
        return $this->belongsTo(Status::class, 'new_value');
    }

    public function oldRetentionStatus()
    {
        return $this->belongsTo(RetentionStatus::class, 'old_value');
    }

    public function newRetentionStatus()
    {
        return $this->belongsTo(RetentionStatus::class, 'new_value');
    }

}
