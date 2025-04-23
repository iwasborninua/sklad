<?php

namespace App\Jobs;

use App\SyncQuantityService\SyncQuantityFrom2;
use App\SyncQuantityService\SyncQuantityFrom3;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class SyncQuantityJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $time = now()->subSeconds(15);

        (new SyncQuantityFrom3())->sync($time);
        (new SyncQuantityFrom2())->sync($time);
    }
}
