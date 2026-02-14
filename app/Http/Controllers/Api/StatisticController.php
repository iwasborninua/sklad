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

    public function pdf(Request $request)
    {
        $data = $request->validate([
            'from'  => ['required', 'date'],
            'to'    => ['required', 'date'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:500'],
        ]);

        $from  = \Carbon\Carbon::parse($data['from'])->startOfDay();
        $to    = \Carbon\Carbon::parse($data['to'])->endOfDay();
        $limit = $data['limit'] ?? 50;

        $rows = \DB::table('site_search')
            ->select('search as tag', \DB::raw('COUNT(*) as qty'))
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('search')
            ->orderByDesc('qty')
            ->limit($limit)
            ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('dashboard.statistics.pdf', compact('from', 'to', 'rows'))
            ->setPaper('a4', 'portrait');



        $filename = 'search_stat_' . $from->format('Ymd') . '_' . $to->format('Ymd') . '.pdf';

        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }

}
