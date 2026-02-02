<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;

class StatisticsController extends Controller
{
    public function index()
    {
        return view('dashboard.statistics.index');
    }
}
