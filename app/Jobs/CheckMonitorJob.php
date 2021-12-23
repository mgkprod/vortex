<?php

namespace App\Jobs;

use App\Events\MonitorDownEvent;
use App\Events\MonitorRecoveredEvent;
use App\Models\Heartbeat;
use App\Models\Monitor;
use App\Notifications\MonitorUpNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class CheckMonitorJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected Monitor $monitor,
    ) {
    }

    public function handle()
    {
        $heartbeat = new Heartbeat();
        $heartbeat->monitor()->associate($this->monitor);

        if ($this->monitor->type == Monitor::TYPE_HTTP || $this->monitor->type == Monitor::TYPE_KEYWORD) {
            $response = Http::get($this->monitor->configuration['host']);

            $heartbeat->response_time = $response->handlerStats()['total_time'];
            $heartbeat->data = ['httpCode' => $response->handlerStats()['http_code']];
        }

        switch ($this->monitor->type) {
            case Monitor::TYPE_HTTP:
                $heartbeat->status = $response->successful() ? Heartbeat::STATUS_UP : Heartbeat::STATUS_DOWN;

                break;
            case Monitor::TYPE_KEYWORD:
                $contains = Str::contains($response->body(), $this->monitor->configuration['keyword']);

                if (
                    ($this->monitor->configuration['fails'] == 'exists' && $contains)
                    || ($this->monitor->configuration['fails'] == 'missing' && ! $contains)
                 ) {
                    $heartbeat->status = Heartbeat::STATUS_DOWN;
                } else {
                    $heartbeat->status = Heartbeat::STATUS_UP;
                }

                break;
            case Monitor::TYPE_PORT:
                $responseTime = ping_host(
                    $this->monitor->configuration['host'],
                    $this->monitor->configuration['port']
                );

                $heartbeat->status = $responseTime != -1 ? Heartbeat::STATUS_UP : Heartbeat::STATUS_DOWN;
                $heartbeat->response_time = ($responseTime / 1000);

                break;
        }

        $latestHeartbeat = $this->monitor->latestHeartbeat;

        $heartbeat->save();

        if (! $latestHeartbeat || $heartbeat->status != $latestHeartbeat->status) {
            $instance = $heartbeat->status
                ? (! $latestHeartbeat
                    ? new MonitorUpNotification()
                    : new MonitorRecoveredEvent($this->monitor
                        ->heartbeats()
                        ->where('status', Heartbeat::STATUS_UP)
                        ->take(1)
                        ->first()))
                : new MonitorDownEvent($heartbeat);
            $this->monitor->notify($instance);
        }
    }
}
