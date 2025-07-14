<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Manufacturer;
use App\Models\Sklad\CategorysSettings;
use App\Models\Sklad\ManufacturersSettings;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isEmpty;

class SettingsController extends Controller
{
    public function syncManufacturers()
    {
        $settings_manufacturers = ManufacturersSettings::select('manufacturer_id')->get();
        $manufacturers_ids = $settings_manufacturers->pluck('manufacturer_id')->toArray();

        $es2 = Manufacturer::select('manufacturer_id', 'name')
            ->whereNotIn('manufacturer_id', $manufacturers_ids)
            ->get()
            ->toArray();

        if (empty($es2)) {
            return response()->json(['message' => 'Нечего синхронизировать'], 200);
        } else {
            foreach ($es2 as $manufacturer) {
                ManufacturersSettings::create([
                    'manufacturer_id' => $manufacturer['manufacturer_id'],
                    'name' => $manufacturer['name'],
                    'active' => false,
                ]);
            }

            return response()->json(['message' => 'Синхронизация производителей завершена успешно'], 200);
        }
    }

    public function syncCategories(Request $request)
    {
        $settings_categories = CategorysSettings::select('category_id')->get();
        $settings_ids = $settings_categories->pluck('category_id')->toArray();

        $es2 = Category::from('oc_category as c')
                ->select('c.category_id', 'ocd.name')
                ->whereNotIn('c.category_id', $settings_ids)
                ->where('c.status', 1)
                ->where('ocd.language_id', 2) // Assuming language_id 2 is for Russian
                ->join('oc_category_description as ocd', 'c.category_id', '=', 'ocd.category_id')
                ->get()
                ->toArray();


        if (count($es2) === 0) {
            return response()->json(['message' => 'Нечего синхронизировать'], 200);
        } else {
            foreach ($es2 as $category) {
                CategorysSettings::create([
                    'category_id' => $category['category_id'],
                    'name' => $category['name'],
                    'active' => false,
                ]);
            }

            return response()->json(['message' => 'Синхронизация категорий завершена успешно'], 200);
        }

    }

    public function updateManufacturers(Request $request)
    {
        $manufacturer = ManufacturersSettings::where('manufacturer_id', $request->input('manufacturer_id'))
            ->first();

        $manufacturer->active = (int)$request->input('active');

        if (!$manufacturer) {
            return response()->json(['message' => 'Производитель не найден'], 404);
        }

        if ($manufacturer->save()) {
            return response()->json(['message' => 'Производитель обновлен успешно'], 200);
        } else {
            return response()->json(['message' => 'Ошибка при обновлении производителя'], 500);
        }
    }

    public function updateCategories(Request $request)
    {

        $category = CategorysSettings::where('category_id', $request->input('category_id'))
            ->first();

        if (!$category) {
            return response()->json([
                'message' => 'Категория не найдена'
            ], 404);
        }

        $category->active = (int)$request->input('active');


        if (!$category) {
            return response()->json(['message' => 'Категория не найдена'], 404);
        }

        if ($category->save()) {
            return response()->json(['message' => 'Категория обновлена успешно'], 200);
        } else {
            return response()->json(['message' => 'Ошибка при обновлении категории'], 500);
        }
    }
}
