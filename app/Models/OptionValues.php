<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionValues extends Model
{
    protected $table = 'oc_product_option_value';
    protected $primaryKey = 'product_option_value_id';
    public $timestamps = false;
    protected $guarded = null;

    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
