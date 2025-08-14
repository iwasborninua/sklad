<?php

namespace App\Models;

use App\Traits\HasRepo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    use HasFactory, HasRepo;

    protected $connection = 'es3';
    protected $table = 'oc_product';
    protected $primaryKey = 'product_id';
    public $timestamps = ['updated_at'];
    protected $guarded = null;

    public static function getTableProducts($category_id, $manufacturer_id)
    {
        $formatted_data = [];

        $query = self::query();
        $query->where('identifier', '<>', null);
        if ($category_id) {
            $query->leftJoin('oc_product_to_category', 'oc_product.product_id', '=', 'oc_product_to_category.product_id')
                ->where('oc_product_to_category.category_id', $category_id);
        }

        if ($manufacturer_id) {
            $query->where('manufacturer_id', $manufacturer_id);
        }

        $result = $query->with(['description', 'productOptionValues.description'])
            ->get()
            ->toArray();

        foreach ($result as $item) {
                $temp = [];
                $temp['id'] = $item['product_id'];
                $temp['name'] = $item['description']['name'];
                $temp['quantity'] = $item['quantity'];

                foreach ($item['product_option_values'] as $product_option_value) {
                        $temp[$product_option_value['description']['name']] = $product_option_value['quantity'];
                }

                $temp['identifier'] = $item['identifier'];
                $formatted_data[] = $temp;
        }

        return $formatted_data;
    }

    public static function getActiveProducts($category_id = null, $manufacturer_id = null)
    {

        $category_id = $category_id == 'all' ? null : $category_id;
        $manufacturer_id = $manufacturer_id == 'all' ? null : $manufacturer_id;

        $query = self::query()
            ->select('oc_product.product_id as id', 'quantity', 'identifier', 'oc_product_description.name as name');

        if ($category_id) {
            $query->leftJoin('oc_product_to_category', 'oc_product.product_id', '=', 'oc_product_to_category.product_id')
                ->where('oc_product_to_category.category_id', $category_id);
        }

        if ($manufacturer_id) {
            $query->where('manufacturer_id', $manufacturer_id);
        }

        $query->leftJoin('oc_product_description', 'oc_product.product_id', '=', 'oc_product_description.product_id')
            ->where('oc_product_description.language_id', 1);


        $products = $query->get()->toArray();

         dd($products);

        return $products;
    }

    public static function getProductOptionsName($manufacturerId = null)
    {
        $settingsOptionsIds = \App\Models\Sklad\OptionsSettings::getActiveOptionsIds();

        $products = self::select(
                'oc_option_value_description.name as option_value_name',
            )
            ->when($manufacturerId, function ($query) use ($manufacturerId) {
                return $query->where('oc_product.manufacturer_id', $manufacturerId);
            })
            ->where('oc_option_value_description.language_id', 1)
            ->leftJoin('oc_product_option', 'oc_product_option.product_id', '=', 'oc_product.product_id')
            ->leftJoin('oc_product_option_value', 'oc_product_option_value.product_option_id', '=', 'oc_product_option.product_option_id')
            ->leftJoin('oc_option_value_description', 'oc_option_value_description.option_value_id', '=', 'oc_product_option_value.option_value_id')
            // Нужно хуйнуть в опции.
//            ->whereNotIn('oc_option_value_description.name',[0, 6, 7, 8, 13, 19, 26, 30, 35, 36, 41, 125, 420, 437, 946])
            ->whereIn('oc_product_option_value.option_value_id', $settingsOptionsIds)
            ->distinct()
            ->get();

        $products->each(function ($product) {
            $product->option_value_name = $product->option_value_name;
        });

        $products = $products->pluck('option_value_name')->unique()->sort()->values();

        return $products;
    }


    public static function getAllProductsOptions($names)
    {
        $products = self::select(
//            'oc_option_value_description.name as option_value_name',
            'oc_option_value_description.*'
        )
            ->where('oc_option_value_description.language_id', 1)
//            ->where('oc_option_value_description.option_id', 13) // если нужно фильтровать по типу опции, раскомментируй эту строку
            ->whereNotIn('oc_option_value_description.name', $names) // исключаем опции, которые не нужны
            ->leftJoin('oc_product_option', 'oc_product_option.product_id', '=', 'oc_product.product_id')
            ->leftJoin('oc_product_option_value', 'oc_product_option_value.product_option_id', '=', 'oc_product_option.product_option_id')
            ->leftJoin('oc_option_value_description', 'oc_option_value_description.option_value_id', '=', 'oc_product_option_value.option_value_id')
            ->distinct()
            ->orderBy('oc_option_value_description.name', 'asc')
            ->get();

        return $products;
    }

    public static function productWithOptions()
    {
        return self::query()->whereNotNull('identifier')
            ->with(['description', 'productOptionValues.value.description', 'productOptionValues.value.option.description']);
    }

    public function options()
    {
        return $this->hasMany(ProductOption::class, 'product_id', 'product_id');
    }

    public function descriptions()
    {
        return $this->hasMany(ProductDescription::class, 'product_id', 'product_id');
    }
    public function description()
    {
        return $this->hasOne(ProductDescription::class, 'product_id', 'product_id')
            ->where('language_id', 1);
    }


    public function productOptionValues(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProductOptionValue::class, 'product_id', 'product_id');
    }
}
