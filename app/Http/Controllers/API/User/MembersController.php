<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\API\APIController;
use App\Http\Requests\Clinics\UpdateOrCreateNewEmployeeRequest;
use App\Http\Resources\LoginResource;
use App\Http\Resources\NewEmployeeResource;
use App\Http\Resources\PharmacistResource;
use App\Http\Resources\UserApp\UserMembersResource;
use App\Models\Clinic;
use App\Models\ClinicSpecialist;
use App\Models\User;
use App\Models\UsersMember;
use App\Models\WorkingDays;
use App\Repositories\App\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MembersController extends APIController
{
    public $repository;

    function __construct(Request $request, UserRepository $repository)
    {
        $this->setLang($request->header('lang'));
        $this->repository = $repository;
    }


    //  get employees
    function index(Request $request)
    {
        $user = $this->repository->checkJwtAuth($request);
        if (!$user) {
            return $this->respondForbidden(trans('messages.auth.user_check'));
        }
        $employees = User::where('parent_id', $user->id)->orderBy('id', 'desc')->select('id', 'name', 'image', 'phone', 'gender','ID_Number','parent_id','dob','file_number','national_id')->paginate(20);
        $employees_list = UserMembersResource::collection($employees)->response()->getData();
        return $this->success(trans('messages.employees.all'), $employees_list);
    }

    // new employee
    public function updateOrCreate(Request $request)
    {
        $check_authorization = $this->repository->checkJwtAuth($request);
        if (!$check_authorization) {
            return $this->respondForbidden(trans('messages.auth.user_check'));
        }
//        if (empty($request->id)) {
//            if (UsersMember::where('phone', $request->phone)->first()) {
//                return $this->respondWithError(trans('messages.auth.phone_exist'));
//            }
//        }
//        if (!empty($request->id)) {
//
//            if (UsersMember::where('phone',$request->phone)->where('id','!=',$request->id)->first()) {
//                return $this->respondWithError(trans('messages.auth.phone_exist'));
//            }
//        }

        $data = $request->all();
        if (empty($request->id)) {
//            $data['phone'] =  $check_authorization->phone;
            $data['parent_id'] =  $check_authorization->id;
            $data['city_id'] =  $check_authorization->city_id;
            $data['password'] =  $check_authorization->password;
            $data['lat'] =  $check_authorization->lat;
            $data['lng'] =  $check_authorization->lng;
            $data['address'] =  $check_authorization->address;
            $data['ID_Number'] = rand(111111111,999999999);
            $data['jwt_token'] = Str::random(75);
            $add_employee = User::create($data);
            if ($add_employee) {
                User::generate_qrCode($add_employee->ID_Number);
            }
        } else {
            $add_employee = User::where('id',$request->id)->first();
            $add_employee->update($data);
        }
        if ($add_employee) {
            return $this->respondWithMessage(trans('messages.Added'));
        } else {
            $this->respondWithError(trans('messages.something_went_wrong'));
        }
    }
}
