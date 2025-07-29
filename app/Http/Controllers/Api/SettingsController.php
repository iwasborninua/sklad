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
    /*
     * Поскольку на open cart почистить старые категории нам в падлу,
     * мне дали эту бредовую задачу
     *
     * */
    private function getUnnecessaryManufacturers()
    {
        return [12,13,16,17,19,20,21,24,26,27,29,30,40,41,42,43,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,69,70,71,72,73,74,75,76,77,80,81];
    }

    /*
     * то же самое, что и для getUnnecessaryManufacturers
     *
     * */
    private function getUnnecessaryCategories()
    {
        return [59, 60, 62, 63, 82, 97, 98, 99, 100, 101, 106, 107, 124, 127, 130, 173, 174, 178, 179, 180, 181, 182, 183, 184, 196, 197, 198, 199, 200, 201, 202, 203, 204, 205, 207, 208, 209, 210, 211, 212, 214, 215, 219, 222, 224, 226, 228, 230, 234, 236, 237, 239, 240, 242, 244, 247, 248, 249, 250, 251, 252, 253, 254, 273, 278, 279, 280, 281, 2646, 2647, 2648, 2650, 2668, 2669, 2670];
    }
    public function syncManufacturers()
    {
        // Получаем все manufacturer_id из ManufacturersSettings

        $settings_manufacturers = ManufacturersSettings::select('manufacturer_id')->get();
        $manufacturers_ids = array_merge($settings_manufacturers->pluck('manufacturer_id')->toArray(), $this->getUnnecessaryManufacturers());

        $manufacturers = Manufacturer::select('manufacturer_id', 'name')
            ->whereNotIn('manufacturer_id', $manufacturers_ids)
            ->get()
            ->toArray();


        if (empty($manufacturers)) {
            return response()->json(['message' => 'Нечего синхронизировать'], 200);
        } else {
            foreach ($manufacturers as $manufacturer) {
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
        $settings_ids = array_merge($settings_categories->pluck('category_id')->toArray(), $this->getUnnecessaryCategories());

        $categorys = Category::from('oc_category as c')
                ->select('c.category_id', 'ocd.name')
                ->whereNotIn('c.category_id', $settings_ids)
                ->where('c.status', 1)
                ->where('ocd.language_id', 2) // Assuming language_id 2 is for Russian
                ->join('oc_category_description as ocd', 'c.category_id', '=', 'ocd.category_id')
                ->get()
                ->toArray();


        if (count($categorys) === 0) {
            return response()->json(['message' => 'Нечего синхронизировать'], 200);
        } else {
            foreach ($categorys as $category) {
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

    public function updateOptions(Request $request)
    {
        $option_id = $request->input('option_id');
        $active = (int)$request->input('active');

        $option = \App\Models\Sklad\OptionsSettings::where('option_id', $option_id)->first();

        if (!$option) {
            return response()->json(['message' => 'Опция не найдена'], 404);
        }

        $option->active = $active;

        if ($option->save()) {
            return response()->json(['message' => 'Опция обновлена успешно'], 200);
        } else {
            return response()->json(['message' => 'Ошибка при обновлении опции'], 500);
        }
    }
}
