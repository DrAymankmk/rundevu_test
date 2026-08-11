<?php

namespace App\Repositories\App;
use App\Models\User;
use App\Models\VerificationCode;
use App\Repositories\BaseRepository;

class UserRepository extends BaseRepository
{
    public function checkJwtAuth($input)
    {
        $token = request()->bearerToken();
        return User::where('jwt_token', $token)->first();
    }

    public function checkIfAccountExists($email)
    {
        return User::where('email', $email)->first();
    }

    public function checkIfEmailUpdateExists($input)
    {
        return User::where('email', $input->email)->where('id','!=',$input->id)->first();
    }

    public function checkIfPhoneExists($phone)
    {
        return User::whereIn('phone', $this->phoneVariants($phone))->first();
    }

    public function checkIfPhoneUpdateExists($input)
    {
        return User::where('phone',$input->phone)->where('id','!=',$input->id)->first();
    }


    // send code to phone to verify account
    public function sendCode($input, $flag)
    {
        $data = $input->all();
        // $activation_code = 111111;
         $activation_code = mt_rand(111111,999999);

        if ($input->email) {
            $key = 'email';
            $value = $input->email;

        } else {
            $key = 'phone';
            $value = $input->phone;
        }
        $expirationMinutes = $flag === 'reset_password' ? 10 : 5;


        VerificationCode::updateOrInsert(
            [
                'phone' => $data['phone'] ?? null,
                'status' => 0,
            ],
            [
                 'type_verify' => $flag,
                'phone' => $data['phone'] ?? null,
                'status' => 0,
                'code' => $activation_code,
                'expired_at' => now()->addMinutes($expirationMinutes)->toDateTimeString(),

            ]
        );

        VerificationCode::verificationCode($value, $activation_code, $flag);


        return true;
    }

    // send code to phone when update


    public function verifyAccount($input)
    {

            $key = 'phone';
            $value = $input->phone;
        $user = VerificationCode::where('code', $input->code)->where($key, $value)->where('status', 0)->first();
        if ($user) {
            $user->status = 1;
            $user->save();
//            $check_user = User::where($key, $value)->first();
//            if ($check_user->active == 0) {
//                $check_user->active = 1;
//                $check_user->save();
//            }
            return $user;
        }
        return false;
    }

    public function verifyAccountUpdateProfile($input, $user)
    {
        $check_code = VerificationCode::where('code', $input->code)->where('phone', $input->phone)->where('status', 0)->first();
        if ($check_code) {
            $check_code->status = 1;
            $check_code->save();
            $check_user = User::where('id', $user->id)->first();
            $check_user->phone = $input->phone;
            $check_user->active = 1;
            $check_user->save();
            return $check_code;
        }
        return false;
    }


    public function sendCodeWhenUpdateProfile($input, $user)
    {
        $data = $input->all();
        $send_code_again = VerificationCode::where('phone', $input->phone)->where('status', 0)->first();
        $send_random_code = mt_rand(100000,999999);
        if ($send_code_again) {
            $send_code_again->code = $send_random_code;
            $send_code_again->save();
        } else {
            $data['code'] = $send_random_code;
            $data['phone'] = $input->phone;
            $data['type_verify'] = 'update_profile';
            $data['status'] = 0;
            $data['expired_at'] = NOW();
            VerificationCode::create($data);
        }
        VerificationCode::send_code('phone',$input->phone, $send_random_code, $input);

        return true;
    }



        public function hasRecentResetPasswordAttempt($phone)
    {
        return VerificationCode::whereIn('phone', $this->phoneVariants($phone))
            ->where('type_verify', 'reset_password')
            ->where(function ($query) {
                $query->where('updated_at', '>=', now()->subMonth())
                    ->orWhere(function ($query) {
                        $query->whereNull('updated_at')
                            ->where('created_at', '>=', now()->subMonth());
                    });
            })
            ->exists();
    }

    public function findByPhone($phone)
    {
        return User::whereIn('phone', $this->phoneVariants($phone))->first();
    }

    public function phoneVariants($phone)
    {
        $raw = trim((string) $phone);
        $digits = preg_replace('/\D+/', '', $raw);

        if (strpos($digits, '00') === 0) {
            $digits = substr($digits, 2);
        }

        if (strpos($digits, '966') === 0) {
            $withoutCountryCode = substr($digits, 3);
        } else {
            $withoutCountryCode = $digits;
        }

        $withoutLeadingZero = ltrim($withoutCountryCode, '0');
        $local = '0' . $withoutLeadingZero;
        $international = '966' . $withoutLeadingZero;

        return array_values(array_unique(array_filter([
            $raw,
            $digits,
            $withoutCountryCode,
            $withoutLeadingZero,
            $local,
            $international,
            '+' . $international,
            '00' . $international,
        ])));
    }

}
