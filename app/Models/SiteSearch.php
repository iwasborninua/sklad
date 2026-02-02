<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSearch extends Model
{
    use HasFactory;

    protected $table = 'site_search';

    protected $casts = [
        'created_at' => 'datetime',
    ];

    protected $fillable = [
        'site', 'search', 'created_at', 'updated_at'
    ];
}
