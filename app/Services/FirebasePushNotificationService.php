<?php

namespace App\Services;

use App\Models\Clinic;
use App\Models\Notifications;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FirebasePushNotificationService
{
    public function sendToUser(int $userId, string $title, string $body, array $data = []): void
    {
        $tokens = User::where('id', $userId)
            ->whereNotNull('firebase_token')
            ->where('firebase_token', '<>', '')
            ->pluck('firebase_token');

        if ($tokens->isEmpty()) {
            $this->pushLog('warning', 'Firebase direct user notification skipped: no user token found.', [
                'user_id' => $userId,
            ]);
            return;
        }

        $this->pushLog('info', 'Firebase direct user notification sending.', [
            'user_id' => $userId,
            'title' => $title,
            'tokens' => $tokens->map(fn ($token) => $this->maskToken($token))->values()->all(),
        ]);

        $this->sendToTokens($tokens, [
            'title' => $title,
            'body' => $body,
            'data' => array_merge([
                'user_id' => (string) $userId,
                'sound' => 'default',
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
            ], array_map('strval', $data)),
        ]);
    }

    public function sendForNotification(Notifications $notification): void
    {
        $tokens = $this->tokensForNotification($notification)->filter()->unique()->values();

        if ($tokens->isEmpty()) {
            $this->pushLog('warning', 'Firebase notification skipped: no recipient tokens found.', [
                'notification_id' => $notification->id,
                'type' => $notification->type,
                'app_type' => $notification->app_type,
                'user_id' => $notification->user_id,
                'receiver_id' => $notification->receiver_id,
                'clinic_id' => $notification->clinic_id,
            ]);
            return;
        }

        $this->pushLog('info', 'Firebase notification sending.', [
            'notification_id' => $notification->id,
            'type' => $notification->type,
            'app_type' => $notification->app_type,
            'user_id' => $notification->user_id,
            'receiver_id' => $notification->receiver_id,
            'clinic_id' => $notification->clinic_id,
            'title' => $notification->title_ar ?: $notification->title_en,
            'tokens_count' => $tokens->count(),
            'tokens' => $tokens->take(20)->map(fn ($token) => $this->maskToken($token))->all(),
        ]);

        $this->sendToTokens($tokens, [
            'title' => $notification->title_ar ?: $notification->title_en ?: 'Notification',
            'body' => $notification->message_ar ?: $notification->message_en ?: '',
            'data' => [
                'notification_id' => (string) $notification->id,
                'type' => (string) $notification->type,
                'app_type' => (string) $notification->app_type,
                'clinic_id' => (string) ($notification->clinic_id ?? ''),
                'user_id' => (string) ($notification->user_id ?? ''),
                'sound' => 'default',
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
            ],
        ]);
    }

    private function tokensForNotification(Notifications $notification): Collection
    {
        if ((int) $notification->type === 0 && (int) $notification->app_type === 0) {
            return User::whereNotNull('firebase_token')
                ->where('firebase_token', '<>', '')
                ->pluck('firebase_token')
                ->merge(Clinic::whereNotNull('firebase_token')->where('firebase_token', '<>', '')->pluck('firebase_token'));
        }

        if ((int) $notification->app_type === 1) {
            if ($notification->user_id || ((int) $notification->type === 1 && $notification->receiver_id)) {
                return User::where('id', $notification->user_id ?: $notification->receiver_id)
                    ->whereNotNull('firebase_token')
                    ->where('firebase_token', '<>', '')
                    ->pluck('firebase_token');
            }

            return User::whereNotNull('firebase_token')->where('firebase_token', '<>', '')->pluck('firebase_token');
        }

        if ($notification->receiver_id) {
            return Clinic::where('id', $notification->receiver_id)
                ->whereNotNull('firebase_token')
                ->where('firebase_token', '<>', '')
                ->pluck('firebase_token');
        }

        if ($notification->clinic_id) {
            return Clinic::where(function ($query) use ($notification) {
                    $query->where('id', $notification->clinic_id)
                        ->orWhere('parent_id', $notification->clinic_id);
                })
                ->whereNotNull('firebase_token')
                ->where('firebase_token', '<>', '')
                ->pluck('firebase_token');
        }

        if ((int) $notification->type === 0) {
            return Clinic::when((int) $notification->app_type > 0, function ($query) use ($notification) {
                    $query->where('app_type', $notification->app_type);
                })
                ->whereNotNull('firebase_token')
                ->where('firebase_token', '<>', '')
                ->pluck('firebase_token');
        }

        return collect();
    }

    private function sendToTokens(Collection $tokens, array $payload): void
    {
        $credentials = $this->firebaseCredentials();

        if ($credentials) {
            $this->sendToTokensUsingHttpV1($tokens, $payload, $credentials);
            return;
        }

        if (config('services.firebase.credentials')) {
            $this->pushLog('warning', 'Firebase HTTP v1 credentials are configured but invalid; falling back to legacy FCM key.');
        }

        $serverKey = config('services.firebase.server_key')
            ?: env('FIREBASE_SERVER_KEY')
            ?: env('FCM_SERVER_KEY');

        if (!$serverKey) {
            $this->pushLog('warning', 'Firebase notification skipped: missing FIREBASE_SERVER_KEY/FCM_SERVER_KEY.');
            return;
        }

        $tokens->filter()->unique()->chunk(500)->each(function (Collection $chunk) use ($serverKey, $payload) {
            try {
                $response = Http::timeout(5)->withHeaders([
                    'Authorization' => 'key=' . $serverKey,
                    'Content-Type' => 'application/json',
                ])->post('https://fcm.googleapis.com/fcm/send', [
                    'registration_ids' => $chunk->values()->all(),
                    'notification' => [
                        'title' => $payload['title'],
                        'body' => $payload['body'],
                        'sound' => 'default',
                        'android_channel_id' => config('services.firebase.android_channel_id', 'default'),
                    ],
                    'data' => $payload['data'],
                    'priority' => 'high',
                    'content_available' => true,
                    'mutable_content' => true,
                ]);

                if ($response->failed()) {
                    $this->pushLog('warning', 'Firebase notification failed.', [
                        'status' => $response->status(),
                        'body' => $response->body(),
                    ]);
                    return;
                }

                $failureCount = (int) $response->json('failure', 0);
                if ($failureCount > 0) {
                    $this->pushLog('warning', 'Firebase notification returned token failures.', [
                        'success' => $response->json('success'),
                        'failure' => $failureCount,
                        'results' => $response->json('results'),
                    ]);
                    return;
                }

                $this->pushLog('info', 'Firebase legacy notification sent.', [
                    'success' => $response->json('success'),
                    'failure' => $failureCount,
                ]);
            } catch (\Throwable $exception) {
                $this->pushLog('error', 'Firebase legacy request exception.', [
                    'exception' => $exception->getMessage(),
                ]);
                report($exception);
            }
        });
    }

    private function sendToTokensUsingHttpV1(Collection $tokens, array $payload, array $credentials): void
    {
        $accessToken = $this->firebaseAccessToken($credentials);

        if (!$accessToken) {
            $this->pushLog('warning', 'Firebase notification skipped: unable to create Firebase access token.');
            return;
        }

        $projectId = $credentials['project_id'];
        $url = 'https://fcm.googleapis.com/v1/projects/' . $projectId . '/messages:send';

        $tokens->filter()->unique()->each(function ($token) use ($accessToken, $payload, $url) {
            try {
                $response = Http::timeout(5)->withToken($accessToken)->post($url, [
                    'message' => [
                        'token' => $token,
                        'notification' => [
                            'title' => $payload['title'],
                            'body' => $payload['body'],
                        ],
                        'data' => $payload['data'],
                        'android' => [
                            'priority' => 'HIGH',
                            'notification' => [
                                'sound' => 'default',
                                'channel_id' => config('services.firebase.android_channel_id', 'default'),
                            ],
                        ],
                        'apns' => [
                            'headers' => [
                                'apns-push-type' => 'alert',
                                'apns-priority' => '10',
                            ] + $this->apnsHeaders(),
                            'payload' => [
                                'aps' => [
                                    'alert' => [
                                        'title' => $payload['title'],
                                        'body' => $payload['body'],
                                    ],
                                    'sound' => 'default',
                                    'badge' => 1,
                                    'content-available' => 1,
                                    'mutable-content' => 1,
                                ],
                            ],
                        ],
                    ],
                ]);

                if ($response->failed()) {
                    $errorCode = collect($response->json('error.details', []))->pluck('errorCode')->filter()->first();

                    $this->pushLog('error', 'Firebase HTTP v1 notification failed.', [
                        'token' => $this->maskToken($token),
                        'status' => $response->status(),
                        'error_status' => $response->json('error.status'),
                        'error_code' => $errorCode,
                        'error_message' => $response->json('error.message'),
                        'hint' => $this->failureHint($response->status(), $errorCode),
                    ]);
                    return;
                }

                $this->pushLog('info', 'Firebase HTTP v1 notification sent.', [
                    'token' => $this->maskToken($token),
                    'message_name' => $response->json('name'),
                ]);
            } catch (\Throwable $exception) {
                $this->pushLog('error', 'Firebase HTTP v1 request exception.', [
                    'token' => $this->maskToken($token),
                    'exception' => $exception->getMessage(),
                ]);
                report($exception);
            }
        });
    }

    private function failureHint(int $status, ?string $errorCode): ?string
    {
        if ($errorCode === 'UNREGISTERED' || $status === 404) {
            return 'Token is no longer valid (app uninstalled or token refreshed). The app must send a new token.';
        }

        if ($errorCode === 'THIRD_PARTY_AUTH_ERROR') {
            return 'APNs rejected the message: upload the APNs Auth Key (.p8) in Firebase Console > Cloud Messaging > Apple app configuration and make sure the bundle ID matches.';
        }

        if ($errorCode === 'SENDER_ID_MISMATCH') {
            return 'Token belongs to a different Firebase project than the service account.';
        }

        if ($errorCode === 'INVALID_ARGUMENT' || $status === 400) {
            return 'Invalid token or payload.';
        }

        if ($status === 401 || $status === 403) {
            return 'Service account is not authorized for this Firebase project.';
        }

        return null;
    }

    private function firebaseCredentials(): ?array
    {
        $path = config('services.firebase.credentials');

        if (!$path) {
            return null;
        }

        if (strpos($path, 'FIREBASE_CREDENTIALS=') === 0) {
            $this->pushLog('warning', 'Firebase HTTP v1 credentials path is misconfigured. Remove the repeated FIREBASE_CREDENTIALS= prefix from .env.', [
                'configured_path' => $path,
            ]);
            return null;
        }

        if (!is_readable($path)) {
            $fallbackPath = storage_path('app/firebase/' . basename($path));

            if ($fallbackPath !== $path && is_readable($fallbackPath)) {
                $this->pushLog('warning', 'Firebase HTTP v1 credentials configured path is not readable; using storage fallback path.', [
                    'configured_path' => $path,
                    'fallback_path' => $fallbackPath,
                ]);

                $path = $fallbackPath;
            }
        }

        if (!is_readable($path)) {
            $this->pushLog('warning', 'Firebase HTTP v1 credentials file is not readable.', [
                'configured_path' => $path,
            ]);
            return null;
        }

        $credentials = json_decode(file_get_contents($path), true);

        if (is_array($credentials) && isset($credentials['project_info'], $credentials['client'])) {
            $this->pushLog('warning', 'Firebase HTTP v1 credentials file is an app config file, not a service account JSON.', [
                'configured_path' => $path,
            ]);
            return null;
        }

        if (
            !is_array($credentials)
            || empty($credentials['project_id'])
            || empty($credentials['client_email'])
            || empty($credentials['private_key'])
        ) {
            $this->pushLog('error', 'Firebase HTTP v1 credentials file is not a service account key.', [
                'configured_path' => $path,
                'keys' => is_array($credentials) ? array_keys($credentials) : 'invalid json',
                'hint' => is_array($credentials) && isset($credentials['project_info'])
                    ? 'This is google-services.json (Android app config). Download a service account key from Firebase Console > Project settings > Service accounts.'
                    : 'The file must contain project_id, client_email and private_key.',
            ]);
            return null;
        }

        return $credentials;
    }

    private function apnsHeaders(): array
    {
        $bundleId = config('services.firebase.apns_bundle_id') ?: env('FCM_APNS_BUNDLE_ID');

        if (!$bundleId) {
            return [];
        }

        return ['apns-topic' => $bundleId];
    }

    private function firebaseAccessToken(array $credentials): ?string
    {
        $cacheKey = 'firebase_access_token_' . sha1($credentials['client_email']);

        return Cache::remember($cacheKey, 3300, function () use ($credentials) {
            $now = time();
            $jwtHeader = $this->base64UrlEncode(json_encode([
                'alg' => 'RS256',
                'typ' => 'JWT',
            ]));
            $jwtClaims = $this->base64UrlEncode(json_encode([
                'iss' => $credentials['client_email'],
                'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
                'aud' => 'https://oauth2.googleapis.com/token',
                'iat' => $now,
                'exp' => $now + 3600,
            ]));
            $unsignedJwt = $jwtHeader . '.' . $jwtClaims;

            $signature = '';
            $signed = openssl_sign($unsignedJwt, $signature, $credentials['private_key'], OPENSSL_ALGO_SHA256);

            if (!$signed) {
                return null;
            }

            $jwt = $unsignedJwt . '.' . $this->base64UrlEncode($signature);

            $response = Http::asForm()->timeout(5)->post('https://oauth2.googleapis.com/token', [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt,
            ]);

            if ($response->failed()) {
                $this->pushLog('warning', 'Firebase OAuth token request failed.', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return null;
            }

            return $response->json('access_token');
        });
    }

    private function pushLog(string $level, string $message, array $context = []): void
    {
        Log::channel('push')->{$level}($message, $context);
    }

    private function maskToken(?string $token): string
    {
        if (!$token) {
            return '';
        }

        return strlen($token) > 20 ? substr($token, 0, 12) . '...' . substr($token, -6) : '***';
    }

    private function base64UrlEncode(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }
}
