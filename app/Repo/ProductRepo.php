<?php

namespace App\Repo;

use Illuminate\Database\Eloquent\Model;

class ProductRepo extends BaseRepo
{
    public static function dataForEditOptionQuantity($data): array
    {
        $res = [];
        foreach ($data as $product) {
            $optionData = [];
            foreach ($product->productOptionValues as $productOptionValue) {
                $optionData[$productOptionValue->value->option->option_id]['name'] = $productOptionValue->value->option->description->name;
                $optionData[$productOptionValue->value->option->option_id]['values'][] = [
                    'product_option_value_id' => $productOptionValue->product_option_value_id,
                    'name' => $productOptionValue->value->description->name,
                    'quantity' => $productOptionValue->quantity,
                ];
            }
            $res[] = [
                'identifier' => $product->identifier,
                'name' => $product->description->name,
                'options' => array_values($optionData)
            ];
        }

        return $res;
    }
}
