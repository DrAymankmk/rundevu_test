<?php

namespace App\Http\Controllers\API;
use App\Http\Resources\SettingResource;
use App\Models\Setting;
use App\Repositories\App\MainRepository;
use Illuminate\Http\Request;

class SettingController extends APIController
{
    public $repository;

    function __construct(Request $request, MainRepository $repository)
    {
        $this->setLang($request->header('lang'));
        $this->repository = $repository;
    }

    //  get setting
    function index(Request $request)
    {
        $check_authorization = $this->repository->checkJwtAuth($request);
        // if (!$check_authorization) {
        //     return $this->respondForbidden(trans('messages.auth.user_check'));
        // }
        $setting_type = !empty($request->settings_type) ? $request->settings_type : 'terms';
        $query = Setting::where(['settings_type'=>$setting_type,'app_type' => null])->select('id', 'content_en','image', 'content_ar');
        // if ($check_authorization->app_type <= 7) {
        //     $query->where('app_type', 1);
        // }
        $setting = $query->first();
        $setting_item = new SettingResource($setting);

        return $this->success(trans('messages.data'), $setting_item);
    }
}
