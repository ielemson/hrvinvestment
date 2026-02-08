<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KycDocument extends Model
{
    protected $fillable = [
        'kyc_id','type','label','file_path','mime','size'
    ];

    public function kyc()
    {
        return $this->belongsTo(Kyc::class);
    }
}
