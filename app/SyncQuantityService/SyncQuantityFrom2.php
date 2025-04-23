<?php

namespace App\SyncQuantityService;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncQuantityFrom2 implements SyncQuantityInterface
{

    public function sync(Carbon $time)
    {
        $data = DB::connection('es2')
            ->table('oc_product')
            ->where('updated_at', '>=', $time)
            ->whereNotNull('identifier')
            ->select(['identifier', 'quantity', 'updated_at'])
            ->get();
        Log::info( count($data), ['-2']);
        foreach ($data as $product) {
            DB::connection('es3')
                ->table('oc_product')
                ->where('identifier', $product->identifier)
                ->update(['quantity' => $product->quantity, 'updated_at' =>  Carbon::parse($product->updated_at)->subSeconds(15)]);
        }
    }
}
