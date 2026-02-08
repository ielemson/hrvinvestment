<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'title','slug','short_description','description',
        'icon','image','cta_text','cta_url',
        'sort_order','is_active','created_by'
    ];
}
