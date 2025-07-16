<?php

namespace App\SyncQuantityService;

use App\Models\ProductOptionValue;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncQuantityFromSunny implements SyncQuantityInterface
{

    public function sync(Carbon $time)
    {
        $data = Product::on('sunny')
            ->with(['productOptionValues' => function ($q) {
                return $q->select(['product_id', 'option_id', 'option_value_id', 'quantity']);
            }])
            ->where('updated_at', '>=', $time)
            ->whereNotNull('identifier')
            ->select(['identifier', 'product_id', 'updated_at', 'quantity'])
            ->get();
        foreach ($data as $product) {
            $product2 = Product::on('es2')->where('identifier', $product->identifier)->first();
            $product2?->update(['quantity' => $product->quantity, 'updated_at' => Carbon::parse($product->updated_at)->subSeconds(15)]);

            $product3 = Product::on('es3')->where('identifier', $product->identifier)->first();
            $product3?->update(['quantity' => $product->quantity, 'updated_at' => Carbon::parse($product->updated_at)->subSeconds(15)]);

            foreach ($product->productOptionValues as $option) {
                if ($product2) {
                    ProductOptionValue::on('es2')
                        ->where('product_id', $product2->product_id)
                        ->where('option_id', $option->option_id)
                        ->where('option_value_id', $option->option_value_id)
                        ->first()?->update(['quantity' => $option->quantity]);
                }

                if ($product3) {
                    ProductOptionValue::on('es3')
                        ->where('product_id', $product3->product_id)
                        ->where('option_id', $option->option_id)
                        ->where('option_value_id', $option->option_value_id)
                        ->first()?->update(['quantity' => $option->quantity]);
                }
            }
        }
    }
}
