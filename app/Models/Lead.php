<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Lead extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'country_id',
        'user_id',
        'user_id_updated_at',
        'created_by',
        'Affiliate',
        'Advert',
        'lead_value',
        'note_updated_at',
        'note',
        'status',
        'retention_status',
        'viewed',
        'changed_by',
        'API',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status', 'id');
    }

    public function retention_status()
    {
        return $this->belongsTo(RetentionStatus::class, 'status', 'id');
    }

    public function statusObject()
    {
        return $this->belongsTo(Status::class, 'status', 'id');
    }

    public function retention_statusObject()
    {
        return $this->belongsTo(RetentionStatus::class, 'retention_status', 'id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function leadComments()
    {
        return $this->hasMany(LeadComment::class);
    }

    public function leadPayments()
    {
        return $this->hasMany(LeadPayment::class);
    }

    public function changes()
    {
        return $this->hasMany(LeadChange::class);
    }

    public function calculateTotalValue()
    {
        $this->lead_value = $this->leadPayments->sum('amount');
        $this->save();
    }

    protected static function booted()
    {
        static::created(function ($lead) {
            // Логирование создания лида
            $lead->logChange('created');
        });

    }

    public function logChange($type, $oldValue = null, $newValue = null)
    {
        $changeDate = now();
        if ($type == 'user_id_changed') {
            $changeDate = $changeDate->addSecond();
        }

        $this->changes()->create([
            'change_type' => $type,
            'changed_by' => Auth::check() ? Auth::id() : 44,
            'old_value' => $oldValue,
            'new_value' => $newValue,
            'change_date' => $changeDate
        ]);
    }

    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = Status::find($value) ? $value : 1;
    }

    public function apiKey()
    {
        return $this->belongsTo(ApiKey::class);
    }
}
