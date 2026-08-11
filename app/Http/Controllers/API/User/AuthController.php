<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\API\APIController;
use App\Http\Requests\UserApp\Auth\ActivateAccount;
use App\Http\Requests\UserApp\Auth\ChangePassword;
use App\Http\Requests\UserApp\Auth\CheckPhoneRequest;
use App\Http\Requests\UserApp\Auth\LoginRequest;
use App\Http\Requests\UserApp\Auth\RegisterRequest;
use App\Http\Resources\UserApp\UserlogginResource;
use App\Models\User;
use App\Repositories\App\UserRepository;
use App\Services\LoyaltyPointsService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends APIController
{
    public $repository;
    private $pointsService;

    function __construct(Request $request, UserRepository $repository, LoyaltyPointsService $pointsService)
    {
        $this->setLang($request->header('lang'));
        $this->repository = $repository;
        $this->pointsService = $pointsService;
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
        $data['expired_date'] = date('Y-m-d');
        $create_user = User::create($data);
        if ($create_user) {
            $this->pointsService->award($create_user, 'welcome', [
                'source_type' => User::class,
                'source_id' => $create_user->id,
            ]);
            $login_response = new UserlogginResource($create_user);
            return $this->success(trans('messages.auth.register'), $login_response);
        }
    }

    // login app
    public function login(LoginRequest $request)
    {
        $app_cycle = User::where('email', $request->email)->first();
        if ($app_cycle) {
            $password = password_verify($request->password, $app_cycle->password);
            if ($password) {
                if ($app_cycle->status == 0) {
                    return $this->respondWithError(trans('messages.auth.account_suspended'));
                }
                $app_cycle->jwt_token = Str::random(50) . Carbon::now()->timestamp;
                $app_cycle->firebase_token = $request->firebase_token;
                $app_cycle->save();
                return $this->success(
                    trans('messages.auth.login'),
                    new UserlogginResource($app_cycle)
                );
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
        $flag = !empty($request->flag) ? $request->flag : 2;  // 1 register , 2 forget password
        $account = $this->repository->findByPhone($request->phone);
        if ($flag == 2) {
            if (!$account) {
                return response()->json([
                    'status' => 401,
                    'message' => trans('messages.auth.phone_not_exist')
                ]);
            } else {
                if ($account->status == 0) {
                    return $this->respondWithError(trans('messages.auth.account_suspended'));
                }
                $this->repository->sendCode($request, 'reset_password');
                return response()->json([
                    'status' => 200,
                    'message' => trans('messages.auth.phone_correct')
                ]);
            }
        } else {
            if (!$account) {
                $this->repository->sendCode($request, 'active');
                return response()->json([
                    'status' => 200,
                    'message' => trans('messages.auth.phone_not_exist')
                ]);
            } else {
                if ($account->status == 0) {
                    return $this->respondWithError(trans('messages.auth.account_suspended'));
                }

                if ($this->repository->hasRecentResetPasswordAttempt($request->phone)) {
                    return response()->json([
                        'status' => 401,
                        'message' => trans('messages.auth.reset_password_monthly_limit')
                    ]);
                }
                return response()->json([
                    'status' => 401,
                    'message' => trans('messages.auth.phone_correct')
                ]);
            }
        }

    }



    public function check_code(ActivateAccount $request)
    {
        if ($request->type == 'profile') {
            $check_user = $this->repository->checkJwtAuth($request);
            if (!$check_user) {
                return $this->respondForbidden(trans('messages.auth.user_check'));
            }
            $user = $this->repository->verifyAccountUpdateProfile($request, $check_user);
        } else {
            $user = $this->repository->verifyAccount($request);
        }

        if (!$user) {
            return $this->respondWithError(trans('messages.auth.wrong_activate_code'));
        }
        if ($request->type == "profile") {
            $key = 'phone';
            $value = $request->phone;
            $user = User::where($key, $value)->first();
            $login_response = new UserlogginResource($user);
            return $this->respond(200, trans('messages.auth.activated_successfully'), $login_response);
        }
        return $this->respondWithMessage(trans('messages.auth.active_code'));


    }

    //change and Forget Password
    public function forget_password(ChangePassword $request)
    {
        $forget_password = $this->repository->findByPhone($request->phone);
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


    // delete account
    function delete_account(Request $request)
    {
        $user = $this->repository->checkJwtAuth($request);
        if (!$user) {
            return $this->respondForbidden(trans('messages.auth.user_check'));
        }
        $delete_account = User::where('id', $user->id)->delete();
        if ($delete_account) {
            return $this->respondWithMessage('تم حذف بيانات الحساب بنجاح');

        }
        return $this->respondWithError(trans('messages.something_went_wrong'));
    }
}
