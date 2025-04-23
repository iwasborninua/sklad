<?php

namespace App\Console\Commands;

use App\Jobs\SyncQuantityJob;
use Illuminate\Console\Command;

class SyncQuantityCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync-quantity-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        dispatch(new SyncQuantityJob());
    }
}
