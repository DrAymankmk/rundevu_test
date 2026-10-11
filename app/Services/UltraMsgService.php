<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use RuntimeException;

class UltraMsgService
{
    protected $instanceId;
    protected $token;

    public function __construct()
    {
        $this->instanceId = config('ultramsg.instance_id');
        $this->token = config('ultramsg.token');
    }

    public function sendMessage($to, $message)
    {
        $to = trim((string) $to);
        $message = trim((string) $message);

        if ($to === '' || $message === '') {
            throw new InvalidArgumentException('Phone number and message are required.');
        }

        if (!$this->instanceId || !$this->token) {
            throw new InvalidArgumentException('UltraMsg credentials are missing. Configure ULTRAMSG_INSTANCE_ID and ULTRAMSG_TOKEN.');
        }

        $response = Http::asForm()->post("https://api.ultramsg.com/{$this->instanceId}/messages/chat", [
            'token' => $this->token,
            'to' => $to, // format: 2-digit country code + phone, e.g., 201001234567
            'body' => $message,
        ]);

        if ($response->failed()) {
            throw new RuntimeException('UltraMsg request failed with status ' . $response->status() . ': ' . $response->body());
        }

        return $response->json() ?? $response->body();
    }
}
