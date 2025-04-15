<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $connection = 'es3';
    protected $table = 'oc_category';

    public static function getActiveCategories()
    {
        return self::where('status', 1)
            ->get();
    }

    public function description()
    {
        return $this->hasOne(CategoryDescription::class, 'category_id', 'category_id')->where('language_id', 1);
    }
}
