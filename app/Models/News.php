<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'api_source',
        'external_id',
        'source_name',
        'source_url',
        'title',
        'slug',
        'summary',
        'image_url',
        'published_at',
        'fetched_at',
        'is_active',
        'raw_payload',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'fetched_at' => 'datetime',
        'raw_payload' => 'array',
        'is_active' => 'boolean',
    ];
}