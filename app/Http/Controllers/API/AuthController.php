<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\Auth\ChangePassword;
use App\Http\Requests\Auth\CheckPhone;
use App\Http\Requests\Auth\CheckPhoneRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\LoginResource;
use App\Models\Clinic;
use App\Repositories\Auth\AuthRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends APIController
{
    public $repository;

    function __construct(Request $request, AuthRepository $repository)
    {
        $this->repository = $repository;
        $this->setLang($request->header('lang'));
    }

    //  Register
    public function register(RegisterRequest $request)
    {
        $data = $request->all();
        if ($this->repository->checkIfAccountExists($request->email)) {
            return $this->respondWithError(trans('messages.auth.account_exists'));
        }
        if ($this->repository->checkIfPhoneExists($request->phone)) {
            return $this->respondWithError(trans('messages.auth.phone_exist'));
        }
        $data['password'] = Hash::make($request->password);
        $data['jwt_token'] = Str::random(75);
        $create_clinic = Clinic::create($data);
        if ($create_clinic) {
            return response()->json([
                'status' => 200,
                'message' => trans('messages.auth.register'),
                'data' => $this->repository->getLoggedDetails($create_clinic)
            ]);

        }
    }

    // login app
    public function login(LoginRequest $request)
    {

        $app_cycle = Clinic::where('email', $request->email)->first();
        if ($app_cycle) {
            $password = password_verify($request->password, $app_cycle->password);
            if ($password) {
                if ($app_cycle->status == 0) {
                    return $this->respondWithError(trans('messages.auth.account_suspended'));
                }
                $app_cycle->jwt_token = Str::random(50) . Carbon::now()->timestamp;
                $app_cycle->firebase_token = $request->firebase_token;
                if ($request->device_token) {
                    $check_token = Clinic::where('device_token',$request->device_token)->where('id', '!=', $app_cycle->id)->exists();
                    if (!$check_token) {
                        $app_cycle->device_token = $request->device_token;
                    }
                }
                $app_cycle->save();
                if ($app_cycle->app_type == 1) {
                    return $this->success(
                        trans('messages.auth.login'),
                        $this->repository->getLoggedDetails($app_cycle)
                    );
                } else {
                    return $this->success(trans('messages.auth.login'), new LoginResource($app_cycle));
                }

            } else {
                return $this->respondUnauthorized(trans('messages.auth.password'));
            }
        } else {
            return $this->respondUnauthorized(trans('messages.auth.check_email'));
        }
    }


    // Check phone
    public function check_phone(CheckPhoneRequest $request)
    {
        $account = Clinic::where('phone', $request->phone)->first();
        if (!$account) {
            return response()->json([
                'status' => 401,
                'message' => trans('messages.auth.phone_not_exist')
            ]);
        } else {
            if ($account->status == 0) {
                return $this->respondWithError(trans('messages.auth.account_suspended'));
            }
            return response()->json([
                'status' => 200,
                'message' => trans('messages.auth.phone_correct')
            ]);
        }
    }

    // forget password

    //change and Forget Password
    public function forget_password(ChangePassword $request)
    {
        $forget_password = Clinic::where('phone', $request->phone)->first();
        if ($forget_password) {
            $forget_password->password = Hash::make($request->new_password);
            $forget_password->save();
            if ($forget_password) {
                return response()->json([
                    'status' => 200,
                    'message' => trans('messages.auth.forgetPassword')
                ]);
            } else {
                return $this->respondWithError(trans('messages.something_went_wrong'));
            }
        } else {
            return $this->respondWithError(trans('messages.auth.phone_not_exist'));
        }
    }
}
