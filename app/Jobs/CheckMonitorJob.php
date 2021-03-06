<?php

namespace App\Jobs;

use App\Models\Heartbeat;
use App\Models\Monitor;
use App\Notifications\MonitorDownNotification;
use App\Notifications\MonitorRecoveredNotification;
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
            try {
                $response = Http::get($this->monitor->configuration['host']);

                $heartbeat->response_time = $response->handlerStats()['total_time'];
                $heartbeat->data = ['httpCode' => $response->handlerStats()['http_code']];
            } catch (\Throwable $th) {
                $heartbeat->response_time = -1;
                $heartbeat->data = [];
                $heartbeat->status = Heartbeat::STATUS_DOWN;
            }
        }

        switch ($this->monitor->type) {
            case Monitor::TYPE_HTTP:
                if ($heartbeat->response_time == -1) {
                    break;
                }

                $heartbeat->status = $response->successful() ? Heartbeat::STATUS_UP : Heartbeat::STATUS_DOWN;

                break;
            case Monitor::TYPE_KEYWORD:
                if ($heartbeat->response_time == -1) {
                    break;
                }

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
                try {
                    $responseTime = ping_host(
                        $this->monitor->configuration['host'],
                        $this->monitor->configuration['port']
                    );
                } catch (\Throwable $th) {
                    $responseTime = -1;
                }

                $heartbeat->status = $responseTime != -1 ? Heartbeat::STATUS_UP : Heartbeat::STATUS_DOWN;
                $heartbeat->response_time = ($responseTime / 1000);

                break;
        }

        $latestHeartbeat = $this->monitor->latestHeartbeat;

        $heartbeat->save();

        if (! $latestHeartbeat || $heartbeat->status != $latestHeartbeat->status) {
            $instance = $heartbeat->status
                ? (! $latestHeartbeat
                    ? new MonitorUpNotification($heartbeat)
                    : new MonitorRecoveredNotification($heartbeat))
                : new MonitorDownNotification($heartbeat);
            $this->monitor->notify($instance);
        }
    }
}
