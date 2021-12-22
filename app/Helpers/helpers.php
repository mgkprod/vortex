<?php

function ping_host(string $hostname, int $port = 80, float $timeout = 3)
{
    $startTime = microtime(true);
    $file = fsockopen($hostname, $port, $errno, $errstr, $timeout);
    $stopTime = microtime(true);
    $status = 0;

    if (! $file) {
        $status = -1;
    } else {
        fclose($file);
        $status = ($stopTime - $startTime) * 1000;
        $status = floor($status);
    }

    return $status;
}
