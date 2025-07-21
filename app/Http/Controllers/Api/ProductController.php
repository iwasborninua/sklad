<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductOptionValue;
use App\Repo\ProductRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function list(Request $request)
    {
        $category_id = $request->query('category_id');
        $manufacturer_id = $request->query('manufacturer_id');

        return Product::getTableProducts($category_id, $manufacturer_id);
    }

    public function updateQuantity(string $identifier, int $count)
    {
        $record = Product::where('identifier', $identifier)->first();

        $record->quantity = $count;
        if ($record->save()) {
            return response('', 204);
        } else {
            return response('', 417);
        }
    }

    public function productsWithOptions(Request $request)
    {
        //Добавь условия в тот запрос, если нужно
        $data = Product::productWithOptions()
            ->whereNotNull('identifier')
            ->get();
        return ProductRepo::dataForEditOptionQuantity($data);
    }

    public function updateOptionQuantity(ProductOptionValue $productOptionValue, int $count)
    {
        $productOptionValue->quantity = $count;

        if ($productOptionValue->save()) {
            return response('', 204);
        } else {
            return response('', 417);
        }
    }

    public function updateOption($identifier, $optionName, $value)
    {
        $product_option_value_id = DB::connection('es3')
            ->table('oc_product as p')
            ->leftJoin('oc_product_option_value as pov', 'pov.product_id', '=', 'p.product_id')
            ->leftJoin('oc_option_value_description as ovd', function ($join) {
                $join->on('pov.option_value_id', '=', 'ovd.option_value_id')
                    ->where('ovd.language_id', 1);
            })
            ->where('p.identifier', $identifier)
            ->where('ovd.name', $optionName)
            ->select('pov.product_option_value_id')
            ->first()->product_option_value_id;


            $productOptionValue = ProductOptionValue::where('product_option_value_id', $product_option_value_id)->first();


            $productOptionValue->quantity = $value;
            if ($productOptionValue->save()) {
                return response()->json($productOptionValue, 201);
            } else {
                return response('', 417);
            }
    }
}
