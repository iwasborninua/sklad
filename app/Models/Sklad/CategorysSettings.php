<?php

namespace App\Models\Sklad;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategorysSettings extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $table = 'categorys_settings';
}
