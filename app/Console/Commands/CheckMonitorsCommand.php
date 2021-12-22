<?php

namespace App\Console\Commands;

use App\Jobs\CheckMonitorJob;
use App\Models\Monitor;
use Illuminate\Console\Command;

class CheckMonitorsCommand extends Command
{
    protected $signature = 'monitors:check';

    public function handle()
    {
        Monitor::query()
            ->get()
            ->each(fn ($monitor) => dispatch(new CheckMonitorJob($monitor)));

        return self::SUCCESS;
    }
}
