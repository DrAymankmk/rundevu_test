<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\API\APIController;
use App\Http\Requests\UserApp\Clinics\ClinicRequest;
use App\Http\Requests\UserApp\SubscriptionPackageRequest;
use App\Http\Resources\OffersResource;
use App\Http\Resources\UserApp\ClinicDetailsResource;
use App\Http\Resources\UserApp\ClinicDoctorsResource;
use App\Http\Resources\UserApp\ClinicsResource;
use App\Http\Resources\UserApp\PackagesResource;
use App\Http\Resources\UserApp\SpecialistsClinicsResource;
use App\Models\Clinic;
use App\Models\ClinicOffer;
use App\Models\ComplaintBox;
use App\Models\Package;
use App\Models\Specialty;
use App\Models\SubscriptionsPackageUser;
use App\Repositories\App\UserRepository;
use Illuminate\Http\Request;

class PackagesController extends APIController
{
    public $repository;

    function __construct(Request $request, UserRepository $repository)
    {
        $this->setLang($request->header('lang'));
        $this->repository = $repository;
    }

    //  get packages
    function index(Request $request)
    {
        $check_authorization = $this->repository->checkJwtAuth($request);
        $user_id = $check_authorization->id ?? null;
//        if (!$check_authorization) {
//            return $this->respondForbidden(trans('messages.auth.user_check'));
//        }
        $subscription_package_user = SubscriptionsPackageUser::where('user_id', $user_id)->where('package_id',4)->exists();
        $query = Package::where('status', 1)->select('id', 'name_' . $this->lang . ' as name','duration','price');
        if ($subscription_package_user) {
            $query->where('id','!=',4);
        }
        $packages = $query->paginate(20);
        $packages_list = PackagesResource::collection($packages)->response()->getData();
        return $this->success(trans('user.packages.all'), $packages_list);
    }

    // subscribe advertiser
    public function subscribe(SubscriptionPackageRequest $request)
    {
        $user = $this->repository->checkJwtAuth($request);
        if (!$user) {
            return $this->respondForbidden(trans('messages.auth.user_check'));
        }
        $data = $request->all();
        $package = Package::where('id', $request->package_id)->first();
        $package_time = $package->duration . ' days';
        if ($user->expired_date >= date('Y-m-d')) {
            $subscribe_date = $user->expired_date;
        } else {
            $subscribe_date = date('Y-m-d H:i:s');
        }
         $new_timestamp = strtotime('+' . $package_time, strtotime($subscribe_date));
        $data['user_id'] = $user->id;
        $data['price'] = $package->price;
        $data['paid'] = $package->paid;
        $data['info_payment'] = $request->info_payment ?? null;
        $data['invoice_number'] = $request->invoice_number ?? null;
        $data['expired_date'] = date("Y-m-d H:i:s", $new_timestamp);
        $data['package_id'] = $request->package_id;
        $subscribe_success = SubscriptionsPackageUser::create($data);
        if ($subscribe_success) {
            $user->expired_date = $data['expired_date'];
            $user->package_id = $request->package_id;
            $user->save();
        }
        if ($subscribe_success) {
            $expired_item['expired_date'] = $data['expired_date'];
            return $this->success(trans('user.packages.subscribe'), $expired_item);
        } else {
            $this->respondWithError(trans('messages.something_went_wrong'));
        }
    }

    // get specialist and sub specialist
    function specialists(Request $request)
    {
        $specialties = Specialty::where('status', 1)->where('parent_id', null)->orderBy('id', 'desc')->paginate(20);
        $specialties_list = SpecialistsClinicsResource::collection($specialties)->response()->getData();
        return $this->success(trans('messages.specialties.all'), $specialties_list);
    }

    // get offers
    function offers(Request $request)
    {
        $offers = ClinicOffer::where('clinic_id', $request->id)->select('id', 'title_ar', 'title_en', 'discount')->orderBy('id', 'desc')->paginate(10);
        $offers_list = OffersResource::collection($offers)->response()->getData();
        return $this->success(trans('messages.offers.all'), $offers_list);
    }

    // clinics details
    function clinic_details(ClinicRequest  $request)
    {
        $clinic = Clinic::with(['currentPackage', 'contract'])->where('id', $request->id)->first();
        $clinic_details = new ClinicDetailsResource($clinic);
        return $this->success(trans('messages.data'), $clinic_details);
    }

    // get doctors
    function clinic_doctors(ClinicRequest $request)
    {
        $offers = Clinic::where('parent_id', $request->id)->where('app_type',3)->select('id', 'name', 'image', 'phone')->orderBy('id', 'desc')->paginate(10);
        $offers_list = ClinicDoctorsResource::collection($offers)->response()->getData();
        return $this->success(trans('messages.offers.all'), $offers_list);
    }

    // send complaint
    public function clinic_complaint(ClinicRequest $request)
    {
        $check_authorization = $this->repository->checkJwtAuth($request);
        if (!$check_authorization) {
            return $this->respondForbidden(trans('messages.auth.user_check'));
        }
        $data = $request->all();
        $data['clinic_id'] =  $request->id;
        $data['user_id'] =  $check_authorization->id;
        $send_complaint = ComplaintBox::create($data);
        return $this->respondWithMessage(trans('user.complaints_box.send'));
    }
}
