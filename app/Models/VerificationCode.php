<?php

namespace App\Models;

use InvalidArgumentException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class VerificationCode extends Model
{
    use HasFactory;

    public $fillable = ['phone', 'type_verify', 'status', 'expired_at', 'code','user_id'];
        private const VERIFY_TYPE_RESET_PASSWORD = 'reset_password';


    public static function send_code($key,$value, $code, $input)
    {
        $message = "مرحبًا بك في تطبيق تكافل ✅\nكود التفعيل الخاص بك هو: *$code*\nيرجى عدم مشاركة هذا الكود مع أي شخص.";
        $params = array(
            'token' => 'mrmm9ckrsa8ojdef',
            'to' => '+966'.$value,
            'body' => $message
        );
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.ultramsg.com/instance78179/messages/chat",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => http_build_query($params),
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

//        if ($err) {
//            echo "cURL Error #:" . $err;
//        } else {
//            echo $response;
//        }
    }
    
    
    
    public static function verificationCode($phone, $code, $type = null)
    {
        $phone = trim((string) $phone);
        $code = trim((string) $code);
        $type = trim((string) $type);
        $appId = config('services.fourjawaly.app_id');
        $appSecret = config('services.fourjawaly.app_secret');
        $sender = config('services.fourjawaly.sender');
        $url = config('services.fourjawaly.url', 'https://api-sms.4jawaly.com/api/v1/account/area/sms/send');

        if ($phone === '' || $code === '') {
            throw new InvalidArgumentException('Phone number and verification code are required.');
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
                            'text' => self::messageText($code, $type),
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
            $message = $response->body();

            if (is_array($error)) {
                $message = $error['message'] ?? $error['error'] ?? $message;
            }

            if (is_array($error) && !empty($error['missing_senders'])) {
                $missingSenders = is_array($error['missing_senders'])
                    ? implode(', ', $error['missing_senders'])
                    : $error['missing_senders'];

                $message .= ' Missing senders: ' . $missingSenders;
            }

            throw new RuntimeException('4Jawaly SMS request failed with status ' . $response->status() . ': ' . $message);
        }

        return $response->json() ?? $response->body();

    }

  private static function messageText(string $code, string $type): string
    {
        if ($type === self::VERIFY_TYPE_RESET_PASSWORD) {
            return "تطبيق رانديفو:\nرمز إعادة تعيين كلمة المرور {$code}";
        }

        return "تطبيق رانديفو:\nرمز التحقق الخاص بك هو {$code}";
    }


}
