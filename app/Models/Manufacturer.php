<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manufacturer extends Model
{
    use HasFactory;

    protected $connection = 'es3';
    protected $table = 'oc_manufacturer';
    protected $primaryKey = 'manufacturer_id';

    public static function getActiveManufacturers()
    {
        return self::query()
            ->get();
    }
}
