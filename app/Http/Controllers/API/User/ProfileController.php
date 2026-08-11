<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\API\APIController;
use App\Http\Requests\Auth\ChangePassword;
use App\Http\Requests\UserApp\Auth\UpdateUserProfile;
use App\Http\Resources\UserApp\UserlogginResource;
use App\Models\Clinic;
use App\Models\User;
use App\Repositories\App\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends APIController
{

    public $repository;

    function __construct(Request $request, UserRepository $repository)
    {
        $this->setLang($request->header('lang'));
        $this->repository = $repository;
    }

    // edit profile
    function edit_profile(UpdateUserProfile $request)
    {
        $check_authorization = $this->repository->checkJwtAuth($request);
        if (!$check_authorization) {
            return $this->respondForbidden(trans('messages.auth.user_check'));
        }
        $check_email = User::where('email', $request->email)->first();
        $check_phone = User::where('phone', $request->phone)->first();
        $edit_profile = User::where('id', $check_authorization->id)->first();

        if (!empty($check_email)) {
            if ($check_email->email != $edit_profile->email) {
                return $this->respondWithError(trans('messages.auth.email_another_account'));
            }
        }
        if ($check_phone) {
            $check_phone = User::where('phone', $request->phone)->first();
            if ($check_phone->phone != $edit_profile->phone) {
                return $this->respondWithError(trans('messages.auth.phone_another_account'));
            }
        }
        $data = $request->all();
        $edit_profile->update($data);
        if ($edit_profile) {
            $login_response = new UserlogginResource($edit_profile);
            return $this->success(trans('messages.auth.edit_profile'), $login_response);
        } else {
            return $this->respondWithError(trans('messages.something_went_wrong'));
        }
    }

    //change  Password
    public function change_password(ChangePassword $request)
    {
        $check_authorization = $this->repository->checkJwtAuth($request);
        if (!$check_authorization) {
            return $this->respondForbidden(trans('messages.auth.user_check'));
        }
        if (isset($request->old_password)) {
            $check = Hash::check($request->old_password, $check_authorization->password);
            if (!$check) {
                return $this->respondWithError(trans('messages.profile.wrong_old_password'));
            }
        }
        $data['password'] = Hash::make($request->new_password);
        $check_authorization->update($data);
        return response()->json([
            'status' => 200,
            'message' => trans('messages.auth.forgetPassword')
        ]);
    }


    // refresh token
    public function refresh_firebase_token(Request $request)
    {
        $check_jwt = $this->repository->checkJwtAuth($request);
        if (!$check_jwt) {
            return $this->respondForbidden(trans('messages.auth.user_check'));
        }
        $edit_token = User::where('id', $check_jwt->id)->first();
        $edit_token->firebase_token = $request->firebase_token;
        $edit_token->save();
        if ($edit_token) {
            return response()->json([
                'status' => 200,
                'message' => trans('messages.auth.update_token'),
            ]);
        } else {
            return $this->respondWithError(trans('messages.something_went_wrong'));
        }
    }
}
