<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Clinic;
use App\Models\ClinicSpecialist;
use App\Models\Specialty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    function index()
    {
        $specialties = Specialty::where('parent_id', null)->where('status', 1)->orderBy('id', 'desc')->get();
        $cities = City::where('status', 1)->get();
        return view('welcome', compact('cities', 'specialties'));
    }

    public function register(Request $request)
    {
        $check_account = Clinic::where('phone', $request->phone)->orwhere('email', $request->email)->first();
        if ($check_account) {
            session()->flash('error', 'البريد الالكترونى او رقم الهاتف موجود مسبقا');
            return redirect()->back();
        }
        $data = $request->all();
        $data['password'] = Hash::make($request->password);
        $data['jwt_token'] = Str::random(75);
        $data['app_type'] = 1;
        $create_place = Clinic::create($data);
        if ($create_place) {
            if ($request->specialty_id) {
                foreach ($request->specialty_id as $specialty) {
                    $add_slider = new ClinicSpecialist();
                    $add_slider->clinic_id = $create_place->id;
                    $add_slider->specialty_id = $specialty;
                    $add_slider->type = 1;
                    $add_slider->save();
                }
            }
        }
        session()->flash('success', __('تم التسجيل بنجاح'));
        return redirect()->back();
    }


}
