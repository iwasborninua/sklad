<?php

namespace App\Models\Sklad;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManufacturersSettings extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $table = 'manufacturers_settings';
    protected $fillable = [
        'manufacturer_id',
        'name',
        'active',
    ];
}
