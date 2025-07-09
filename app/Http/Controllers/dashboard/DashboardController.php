<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Manufacturer;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard ()
    {
        $categories = Category::getActiveCategories();
        $manufacturers = Manufacturer::getActiveManufacturers();

        return view('dashboard.index', [
            'categories' => $categories,
            'manufacturers' => $manufacturers,
        ]);
    }
}
