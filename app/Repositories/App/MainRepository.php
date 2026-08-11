<?php

namespace App\Repositories\App;

use App\Models\Clinic;
use App\Repositories\BaseRepository;

class MainRepository extends BaseRepository
{
    public function checkJwtAuth($input)
    {
        $token = request()->bearerToken();
        return Clinic::where('jwt_token', $token)->first();
    }

    public function checkIfAccountExists($email)
    {
        return Clinic::where('email', $email)->first();
    }

    public function checkIfEmailUpdateExists($input)
    {
        return Clinic::where('email', $input->email)->where('id','!=',$input->id)->first();
    }

    public function checkIfPhoneExists($phone)
    {
        return Clinic::where('phone', $phone)->first();
    }

    public function checkIfPhoneUpdateExists($input)
    {
        return Clinic::where('phone',$input->phone)->where('id','!=',$input->id)->first();
    }

}
