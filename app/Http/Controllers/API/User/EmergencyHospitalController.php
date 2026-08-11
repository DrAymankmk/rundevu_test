<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\API\APIController;
use App\Http\Resources\UserApp\EmergencyHospitalResource;
use App\Models\EmergencyHospital;
use App\Repositories\App\UserRepository;
use Illuminate\Http\Request;

class EmergencyHospitalController extends APIController
{
    protected $repository;

    public function __construct(Request $request, UserRepository $repository)
    {
        $this->setLang($request->header('lang'));
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $user = $this->repository->checkJwtAuth($request);

        $lat = $request->lat ?? optional($user)->lat;
        $lng = $request->lng ?? optional($user)->lng;

        if ($lat && $lng) {
            $query = EmergencyHospital::nearby($lat, $lng, $request->radius ?? 50);
        } else {
            $query = EmergencyHospital::where('status', 1)->latest();
        }

        $query->with(['city', 'region']);

        if ($request->q) {
            $query->where(function ($searchQuery) use ($request) {
                $searchQuery->where('name_ar', 'like', '%' . $request->q . '%')
                    ->orWhere('name_en', 'like', '%' . $request->q . '%');
            });
        }

        if ($request->city_id) {
            $query->where('city_id', $request->city_id);
        }

        if ($request->region_id) {
            $query->where('region_id', $request->region_id);
        }

        $hospitals = $query->paginate(20);
        $data = EmergencyHospitalResource::collection($hospitals)->response()->getData();

        return $this->success(trans('messages.data'), $data);
    }
}
