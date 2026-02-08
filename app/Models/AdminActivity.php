<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_id',
        'admin_user_id',
        'action',
        'target_type',
        'target_id',
        'target_user',
        'status'
    ];

    public function adminUser()
    {
        return $this->belongsTo(User::class, 'admin_user_id');
    }

    public static function generateId()
    {
        return 'A-' . str_pad(mt_rand(100, 999), 3, '0', STR_PAD_LEFT);
    }
}
