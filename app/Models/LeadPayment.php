<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadPayment extends Model
{
    protected $fillable = ['user_id', 'lead_id', 'amount'];

    public function leadForDashboard()
    {
        return $this->belongsTo(Lead::class, 'lead_id', 'id');
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
    protected static function booted()
    {
        static::saved(function ($payment) {
            $payment->lead->calculateTotalValue();
        });

        static::deleted(function ($payment) {
            $payment->lead->calculateTotalValue();
        });
    }

}
