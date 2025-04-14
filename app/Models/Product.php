<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $connection = 'es3';
    protected $table = 'oc_product';

    public static function getActiveProducts($category_id = null, $manufacturer_id = null)
    {
        return self::query()
            ->select('product_id', 'quantity', 'stock_status_id', 'manufacturer_id',)
            ->where('stock_status_id', 7)
            ->when($category_id, function ($query) use ($category_id) {
                return $query->where('category_id', $category_id);
            })
            ->when($manufacturer_id, function ($query) use ($manufacturer_id) {
                return $query->where('manufacturer_id', $manufacturer_id);
            })
            ->get();
    }
}
