<?php

namespace App\Helpers;

use GuzzleHttp\Client;

class Discord
{
    public function __construct(
        protected string $discordWebhookUrl,
    ) {
    }

    public function webhook($content, $embed = null, $type = null)
    {
        $payload = [
            'json' => [
                'username' => config('app.name'),
                'avatar_url' => asset('images/square.png'),
                'content' => $content,
            ],
        ];

        if ($content == null) {
            unset($payload['json']['content']);
        }

        if ($embed) {
            switch ($type) {
                case 'info':
                    $embed['color'] = hexdec('0EA5E9');
                    break;
                case 'warning':
                    $embed['color'] = hexdec('F59E0B');
                    break;
                case 'error':
                    $embed['color'] = hexdec('EF4444');
                    break;
                case 'success':
                    $embed['color'] = hexdec('EC4899');
                    break;
                default:
                    $embed['color'] = hexdec('6B7280');
                    break;
            }

            $payload['json']['embeds'][] = $embed;
        }

        $client = new Client();
        $client->post($this->discordWebhookUrl, $payload);
    }
}
