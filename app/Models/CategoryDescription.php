<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryDescription extends Model
{
    use HasFactory;

    protected $connection = 'es3';
    protected $table = 'oc_category_description';
}
