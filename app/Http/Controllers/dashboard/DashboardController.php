<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Manufacturer;
use App\Models\Sklad\CategorysSettings;
use App\Models\Sklad\ManufacturersSettings;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard ()
    {
        $categories = CategorysSettings::select('id','category_id', 'name')
            ->where('active', true)
            ->get();

        $manufacturers = ManufacturersSettings::
            select('id', 'manufacturer_id', 'name')
            ->where('active', true)
            ->get();

        return view('dashboard.index', [
            'categories' => $categories,
            'manufacturers' => $manufacturers,
        ]);
    }
}
