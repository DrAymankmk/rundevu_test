<?php

namespace App\Http\Controllers\API\User\Clinics;

use App\Http\Controllers\API\APIController;
use App\Http\Resources\UserApp\RatingResource;
use App\Models\ClinicRating;
use App\Models\Rating;
use App\Repositories\App\UserRepository;
use App\Services\LoyaltyPointsService;
use Illuminate\Http\Request;

class RatingController extends APIController
{
    public $repository;
    private $pointsService;

    function __construct(Request $request, UserRepository $repository, LoyaltyPointsService $pointsService)
    {
        $this->setLang($request->header('lang'));
        $this->repository = $repository;
        $this->pointsService = $pointsService;
    }

    //  get rating
    function index(Request $request)
    {
        $query = Rating::where('status', 1)->select('id', 'name_' . $this->lang . ' as name')->paginate(20);
        $rating_list = RatingResource::collection($query)->response()->getData();
        return $this->success('rating_list', $rating_list);
    }

    // post rate in clinic
    function make_rate(Request $request)
    {
        $user = $this->repository->checkJwtAuth($request);
        if (!$user) {
            return $this->respondForbidden(trans('messages.auth.user_check'));
        }
        $i=0;
        $lastRate = null;
        foreach ($request->rate_id as $rating) {

            $lastRate = ClinicRating::updateOrCreate(
                [
                    'clinic_id' => $request->id,
                    'user_id' => $user->id,
                    'rating_id' => $rating,
                ],
                [
                    'clinic_id' => $request->id,
                    'user_id' => $user->id,
                    'rating_id' => $rating,
                    'comment' => !empty($request->comment) ? $request->comment : "",
                    'rate_value' => $request->rate[$i],
                ]
            );
//            $add_rate = new ClinicRating();
//            $add_rate->user_id = $user->id;
//            $add_rate->clinic_id = $request->id;
//            $add_rate->rating_id = $rating;
//            $add_rate->comment = $request->comment;
//            $add_rate->rate_value = $request->rate[$i];
//            $add_rate->save();
            $i++;
        }

        $words = preg_split('/\s+/u', trim(strip_tags((string) $request->comment)), -1, PREG_SPLIT_NO_EMPTY);
        if (count($words) >= 20 && $lastRate) {
            $this->pointsService->award($user, 'rating', [
                'clinic_id' => $request->id,
                'source_type' => ClinicRating::class,
                'source_id' => $lastRate->id,
                'description_ar' => 'تقييم بعد الزيارة',
                'description_en' => 'Visit rating',
            ]);
        }

        return $this->respondWithMessage(trans('messages.rating.send'));

    }
}
