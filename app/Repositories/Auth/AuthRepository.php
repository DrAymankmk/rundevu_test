<?php

namespace App\Repositories\Auth;

use App\Address;
use App\Helper\ConstantHelper;
use App\Helper\AppHelper;
use App\Models\Clinic;

use App\Models\ComplaintBox;
use App\Repositories\BaseRepository;

class AuthRepository extends BaseRepository
{
    public function getLoggedDetails($user)
    {
        $data['id'] = $user['id'];
        $data['department_id'] = (int)$user['app_type'];
        $data['name'] = $user['name'];
        $data['email'] = $user['email'];
        $data['phone'] = $user['phone'];
        $data['image'] = $user['image'];
        $data['qr_code'] = $user['qr_code'];
        $data['date_created'] = $user['date_created'] ?? "";
        $data['city_id'] = (int)$user['city_id'];
        $data['lat'] = (string)$user['lat'] ?? "";
        $data['lng'] = (string)$user['lng'] ?? "";
        $data['address'] = $user['address'] ?? "";
        $data['info'] = $user['info'] ?? "";
        $data['communication_officer'] = $user['communication_officer'] ?? "";
        $data['social_media'] = [
            'facebook' => $user['facebook_url'] ?? "",
            'instagram' => $user['instagram_url'] ?? "",
            'tiktok' => $user['tiktok_url'] ?? "",
            'snapchat' => $user['snapchat_url'] ?? "",
            'youtube' => $user['youtube_url'] ?? "",
        ];
        $data['jwt_token'] = $user['jwt_token'];
        $data['points'] =  0;
        $data['posts_per_week'] =  3;
        $data['notifications_count'] =  0;
        $data['complains_count'] =  ComplaintBox::where('clinic_id',$user['id'])->count();
        return $data;
    }


    public function checkIfAccountExists($email)
    {
        return Clinic::where('email', $email)->first();
    }

    public function checkIfPhoneExists($phone)
    {
        return Clinic::where('phone', $phone)->first();
    }

    public function checkIfEmailExists($email)
    {
        $user = Clinic::where('email', $email)->first();
        if ($user) {
            return 'true';
        }
        return 'false';
    }

//    public function checkJwtUser($input)
//    {
//        $jwt = AppHelper\::getKeyHeader($input, ConstantHelper::$jwt, "");
////        $jwt = $input['jwt'];
//        $check_account = Clinic::where('jwt_token', $jwt)->first();
//        return $check_account;
//    }

}
