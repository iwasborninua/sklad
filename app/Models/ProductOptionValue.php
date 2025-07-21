<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOptionValue extends Model
{
    protected $table = 'oc_product_option_value';
    protected $primaryKey = 'product_option_value_id';
    public $timestamps = false;
    protected $guarded = null;

    protected $connection = 'es3';

    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function value()
    {
        return $this->hasOne(OptionValue::class, 'option_value_id', 'option_value_id');
    }

    public function option()
    {
        return $this->hasOne(Option::class, 'option_id', 'option_id');
    }

    public function description()
    {
        return $this->hasOne(OptionValueDescription::class, 'option_value_id', 'option_value_id')
            ->where('language_id', config('constants.lang'));
    }
}
