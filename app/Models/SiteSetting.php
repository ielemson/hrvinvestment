<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
 // app/Models/SiteSetting.php
protected $fillable = [
    'site_name',
    'contact_phone',
    'contact_email',
    'contact_address',
    'about_us',
    'logo_path',
    'logo_mini_path',
    'favicon_path',
    'meta_title',
    'meta_description',
    'meta_keywords',
    'og_image_path',
    'currency_code',
    'currency_symbol',
    'currency_position',
    'thousand_separator',
    'decimal_separator',
    'decimal_places',
];

}
