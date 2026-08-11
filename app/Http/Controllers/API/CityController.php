<?php

namespace App\Http\Controllers\API;
use App\Http\Resources\CitiesResource;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends APIController
{
    function __construct(Request $request)
    {
        $this->setLang($request->header('lang'));
    }

    // get cities
    function index()
    {
        // $cities_list = City::where('status', 1)->select('id', 'name_' . $this->lang . ' as name')->paginate();

        $cities = City::where('status', 1)->select('id', 'name_' . $this->lang . ' as name')->paginate(20);
        $cities_list = CitiesResource::collection($cities)->response()->getData();

        return $this->success(trans('messages.cities.all'), $cities_list);
    }
}
