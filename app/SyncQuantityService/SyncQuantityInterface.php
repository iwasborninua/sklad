<?php

namespace App\SyncQuantityService;

use Carbon\Carbon;

interface SyncQuantityInterface
{
    public function sync(Carbon $time);
}
