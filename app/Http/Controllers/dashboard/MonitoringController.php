<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class MonitoringController extends Controller
{
    public function index()
    {
        $resources = [
            'errorsseeds-mail.com',
            'medcannabis.info',
            'ruhemp.com',
            'co-semena.info',
            'cannamarch.com',
            'citrys.info',
            'jahnews.nl',
            'jahgrow.com',
            'jahfunny.net',
            'growtools.pro',
            'growblog.pro',
            'cannabugs.com',
            'cannabis-outdoor.net',
            'cannabis-indoor.net',
            'jahstrains.com',
            'cannagrowing.net'
        ];

        return view('dashboard.monitoring' , compact('resources'));
    }

    public function check(Request $request)
    {
        $domain = $request->input('domain');

        $promise = Http::async()->get("https://$domain");

        try {
            $response = $promise->wait();
            $statusCode = $response->status();
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error: ' . $e->getMessage(),
                'statusCode' => 0,
            ]);
        }

        $formatted_response = [
            'statusCode' => $statusCode,
            'class' => ($statusCode >= 200 && $statusCode < 300) ? 'bg-success' : (($statusCode >= 300 && $statusCode < 400) ? 'bg-warning' : 'bg-danger'),
        ];

        return response()->json($formatted_response);
    }
}
