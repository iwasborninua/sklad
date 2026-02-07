<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SiteSearch;
use function Laravel\Prompts\search;

class StatisticController extends Controller
{
    public function store(Request $request) {
        $row = SiteSearch::create([
            'search' => $request->input('search'),
        ]);

        return response()->json([
            'ok' => true,
            'id' => $row->id,
        ]);
    }

    public function show(Request $request) {
        $limit = $request->input('limit');
        $from  = $request->input('from');
        $to    = $request->input('to');

        $query = SiteSearch::selectRaw('search, COUNT(*) as count');

        if ($from && $to) {
            $query->whereBetween('created_at', [
                $from . ' 00:00:00',
                $to   . ' 23:59:59',
            ]);
        }

        $totalCount = $query->count();

        $data = $query->groupBy('search')
            ->orderBy('count', 'desc')
            ->get();

        $clearData = [];

        foreach ($data as $item) {
            $clearData[] = [
                'search' => $item->search,
                'count' => $item->count,
            ];
        }

        usort($clearData, function ($a, $b) {
            return $b['count'] <=> $a['count'];
        });

        return $clearData;
    }
}
