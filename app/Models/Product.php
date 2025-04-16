<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use function PHPUnit\Framework\isNull;

class Product extends Model
{
    use HasFactory;

    protected $connection = 'es3';
    protected $table = 'oc_product';

    public static function getActiveProducts($category_id = null, $manufacturer_id = null)
    {

        $category_id = $category_id == 'all' ? null : $category_id;
        $manufacturer_id = $manufacturer_id == 'all' ? null : $manufacturer_id;

        $query = self::query()
            ->select('oc_product.product_id', 'quantity', 'identifier', 'name');

            if ($category_id) {
                $query->leftJoin('oc_product_to_category', 'oc_product.product_id', '=', 'oc_product_to_category.product_id')
                    ->where('oc_product_to_category.category_id', $category_id);
            }
            $query->leftJoin('oc_product_description', 'oc_product.product_id', '=', 'oc_product_description.product_id')
                ->where('oc_product_description.language_id', 1)
                ->when($manufacturer_id, function ($query) use ($manufacturer_id) {
                    return $query->where('manufacturer_id', $manufacturer_id);
                });


        $products = $query->get()->toArray();

        return $products;
    }
}
