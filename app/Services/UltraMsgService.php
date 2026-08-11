<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

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
        $response = Http::asForm()->post("https://api.ultramsg.com/{$this->instanceId}/messages/chat", [
            'token' => $this->token,
            'to' => $to, // format: 2-digit country code + phone, e.g., 201001234567
            'body' => $message,
        ]);

        return $response->json();
    }
}
