<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kyc extends Model
{
    protected $fillable = [
        'user_id',
        'full_name',
        'phone',
        'phone_country_iso',
        'phone_e164',
        'phone_national',
        'phone_country_code',
        'gender',
        'address',
        'country',
        'city',
        'state',
        'status',
        'rejection_reason',
        'reviewed_by',
        'reviewed_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function documents()
    {
        return $this->hasMany(KycDocument::class);
    }

    public function documentOf(string $type)
    {
        return $this->documents()->where('type', $type)->latest()->first();
    }
}