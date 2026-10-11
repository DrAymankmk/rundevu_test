<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use RuntimeException;

class FourJawalyService
{
    public function sendMessage($phone, string $message)
    {
        $phone = trim((string) $phone);
        $message = trim($message);
        $appId = config('services.fourjawaly.app_id');
        $appSecret = config('services.fourjawaly.app_secret');
        $sender = config('services.fourjawaly.sender');
        $url = config('services.fourjawaly.url', 'https://api-sms.4jawaly.com/api/v1/account/area/sms/send');

        if ($phone === '' || $message === '') {
            throw new InvalidArgumentException('Phone number and message are required.');
        }

        if (!$appId || !$appSecret) {
            throw new InvalidArgumentException('4Jawaly credentials are missing. Configure FOURJAWALY_APP_ID and FOURJAWALY_APP_SECRET.');
        }

        if (!$sender) {
            throw new InvalidArgumentException('4Jawaly sender is missing. Configure FOURJAWALY_SENDER with a sender name approved in your 4Jawaly account.');
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . base64_encode($appId . ':' . $appSecret),
            ])
                ->acceptJson()
                ->asJson()
                ->timeout((int) config('services.fourjawaly.timeout', 30))
                ->post($url, [
                    'messages' => [
                        [
                            'text' => $message,
                            'numbers' => [$phone],
                            'sender' => $sender,
                        ],
                    ],
                ]);
        } catch (ConnectionException $exception) {
            throw new RuntimeException('4Jawaly SMS request could not connect: ' . $exception->getMessage(), 0, $exception);
        }

        if ($response->failed()) {
            $error = $response->json();
            $responseMessage = $response->body();

            if (is_array($error)) {
                $responseMessage = $error['message'] ?? $error['error'] ?? $responseMessage;
            }

            if (is_array($error) && !empty($error['missing_senders'])) {
                $missingSenders = is_array($error['missing_senders'])
                    ? implode(', ', $error['missing_senders'])
                    : $error['missing_senders'];

                $responseMessage .= ' Missing senders: ' . $missingSenders;
            }

            throw new RuntimeException('4Jawaly SMS request failed with status ' . $response->status() . ': ' . $responseMessage);
        }

        return $response->json() ?? $response->body();
    }
}
