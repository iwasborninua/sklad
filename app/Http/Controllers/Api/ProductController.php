<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductOptionValue;
use App\Repo\ProductRepo;
use Illuminate\Http\Request;

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
}
