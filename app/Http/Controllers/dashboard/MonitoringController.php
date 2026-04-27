<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Throwable;

class MonitoringController extends Controller
{
    public function getResources()
    {
        return $resources = [
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
            'cannagrowing.net',
            'mushrooms-shop.com.ua',
            'errors-seeds.info',
            'errorsseeds-kz.com',
            'growmarket.com.ua',
            'errorsseeds-ge.com',
            'carpathians-seeds.com'
        ];
    }

    public function index()
    {
        $resources = $this->getResources();

        return view('dashboard.monitoring' , compact('resources'));
    }

    public function check()
    {
        $resources = $this->getResources();

        $responses = Http::pool(function ($pool) use ($resources) {
            $requests = [];

            foreach ($resources as $domain) {
                $requests[$domain] = $pool
                    ->as($domain)
                    ->timeout(5)
                    ->connectTimeout(2)
                    ->withOptions([
                        'allow_redirects' => true,
                        'verify' => false,
                    ])
                    ->get('https://' . $domain);
            }

            return $requests;
        });

        $result = [];

        foreach ($resources as $domain) {
            $response = $responses[$domain] ?? null;

            /**
             * Успешный HTTP-ответ Laravel
             */
            if ($response instanceof Response) {
                $result[] = [
                    'domain' => $domain,
                    'url' => 'https://' . $domain,
                    'status' => $response->successful() ? 'ok' : 'bad',
                    'http_code' => $response->status(),
                    'message' => null,
                ];

                continue;
            }

            /**
             * Ошибка подключения: DNS, timeout, SSL, connection refused и т.д.
             */
            if ($response instanceof Throwable) {
                $result[] = [
                    'domain' => $domain,
                    'url' => 'https://' . $domain,
                    'status' => 'error',
                    'http_code' => null,
                    'message' => $response->getMessage(),
                ];

                continue;
            }

            /**
             * На всякий случай, если пришло что-то неожиданное
             */
            $result[] = [
                'domain' => $domain,
                'url' => 'https://' . $domain,
                'status' => 'error',
                'http_code' => null,
                'message' => 'Unknown response type',
            ];
        }

        return response()->json($result);
    }
}
