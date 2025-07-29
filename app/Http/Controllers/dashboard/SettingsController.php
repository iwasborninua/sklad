<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductOption;
use App\Models\sklad\OptionsSettings;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        return view('dashboard.settings');
    }

    public function options()
    {
        $options = OptionsSettings::select('option_id', 'name')
            ->get();


        $product_options = Product::getAllProductsOptions($options->pluck('name')->toArray());

        if (!$product_options->isEmpty()) {
            OptionsSettings::setNewOptions($product_options);
        }

        $options_settings = OptionsSettings::select('option_id', 'name', 'active')
            ->orderByRaw('CAST(name AS UNSIGNED) ASC')
            ->get();

        return view('partials.settings.options', [
            'options' => $options_settings,
        ]);
    }
}
