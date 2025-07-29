<?php

namespace App\Models\sklad;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionsSettings extends Model
{
    use HasFactory;
    protected $table = 'options_settings';
    protected $fillable = [
        'option_id',
        'name',
        'active'
    ];


    public static function setNewOptions($options)
    {
        foreach ($options as $option) {
            $os = new OptionsSettings(['option_id' => $option->option_value_id, 'name' => $option->name, 'active' => 1]);
            $os->save();
        }
    }
}
