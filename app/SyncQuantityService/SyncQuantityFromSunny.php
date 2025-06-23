<?php

namespace App\SyncQuantityService;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncQuantityFromSunny implements SyncQuantityInterface
{

    public function sync(Carbon $time)
    {
        $data = DB::connection('sunny')
            ->table('oc_product')
            ->where('updated_at', '>=', $time)
            ->whereNotNull('identifier')
            ->select(['identifier', 'quantity', 'updated_at'])
            ->get();
        foreach ($data as $product) {
            DB::connection('es2')
                ->table('oc_product')
                ->where('identifier', $product->identifier)
                ->update(['quantity' => $product->quantity, 'updated_at' =>  Carbon::parse($product->updated_at)->subSeconds(15)]);
        }
        foreach ($data as $product) {
            DB::connection('es3')
                ->table('oc_product')
                ->where('identifier', $product->identifier)
                ->update(['quantity' => $product->quantity, 'updated_at' =>  Carbon::parse($product->updated_at)->subSeconds(15)]);
        }
    }
}
