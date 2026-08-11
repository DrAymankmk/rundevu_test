<?php
namespace App\Http\Controllers\AdminPanel;
use App\Http\Requests\Admin\ChangePasswordRequest;
use App\Models\City;
use App\Models\Clinic;
use App\Models\ClinicSpecialist;
use App\Models\DoctorDegree;
use App\Models\Specialty;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class ProfileController extends Controller
{
    // show profile page
    function index()
    {
        if (Auth::user()->app_type == 2 || Auth::user()->app_type == 3 || auth()->user()->app_type == 5 || Auth::user()->app_type == 6  || Auth::user()->app_type == 25 || Auth::user()->app_type == 26 || Auth::user()->app_type == 10) {
            $auth_user = Auth::user()->id;
            $data['degree'] = DoctorDegree::where('status', 1)->select('id', 'name_' . $this->lang() . ' as name')->get();
            $data['specialty_ids'] = ClinicSpecialist::with('specialties')->where('clinic_id', $auth_user)->where('type', 1)->where('status', 1)->orderBy('id', 'desc')->pluck('specialty_id')->toArray();
            $data['sub_specialty_ids'] = ClinicSpecialist::with('specialties')->where('clinic_id', $auth_user)->where('type', 2)->where('status', 1)->orderBy('id', 'desc')->pluck('specialty_id')->toArray();
            $data['specializations'] = Specialty::where('status', 1)->where('parent_id',null)->orderBy('id', 'desc')->get();
            $data['sub_specializations'] = Specialty::whereIn('parent_id',$data['specialty_ids'])->where('status', 1)->where('parent_id','!=',null)->orderBy('id', 'desc')->get();
            $blade = 'doctors.profile.edit_profile';
        } else {
            $auth_user = Auth::user()->app_type == 7 ? Auth::user()->id : Auth::user()->parent_id;
            $data['cities'] = City::where('status', 1)->orderBy('id', 'desc')->get();
            $blade = 'profile.index';
        }
        $data['clinic'] = Clinic::where('id', $auth_user)->first();
        return view($blade, compact('data'));
    }

    // show profile page
    function change_password()
    {
        if (Auth::user()->app_type == 3 || Auth::user()->app_type == 5 || Auth::user()->app_type == 6  || Auth::user()->app_type == 25 || Auth::user()->app_type == 26 || Auth::user()->app_type == 10) {
            $blade = 'doctors.profile.change_password';
        } else {
            $blade = 'profile.change_password';
        }
        return view($blade);
    }

    function edit_profile(Request $request, $id)
    {
        $request->validate([
            'facebook_url' => ['nullable', 'url', 'max:255'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'tiktok_url' => ['nullable', 'url', 'max:255'],
            'snapchat_url' => ['nullable', 'url', 'max:255'],
            'youtube_url' => ['nullable', 'url', 'max:255'],
        ]);

        if (Auth::user()->app_type == 2 || Auth::user()->app_type == 7 || Auth::user()->app_type == 6  || Auth::user()->app_type == 3 || Auth::user()->app_type == 5 || Auth::user()->app_type == 25 || Auth::user()->app_type == 26 || Auth::user()->app_type == 10) {
            $auth_user =  Auth::user()->id;
        } else {
            $auth_user = Auth::user()->parent_id;
        }

        $clinic = Clinic::where('id', $id)->first();
        $auth_user = $clinic->id;
        $check_email = Clinic::where('email', $request->email)->where('id', '!=', $auth_user)->first();
        $check_phone = Clinic::where('phone', $request->phone)->where('id', '!=', $auth_user)->first();
        // 🔥 normalize phone (مهم)
        $phone = preg_replace('/\D/', '', $request->phone);

        // =========================
        // ✅ CHECK EMAIL
        // =========================
        if (!empty($request->email)) {
        if ($request->email !== $clinic->email) {
            $emailExists = Clinic::where('email', $request->email)
                ->where('id', '!=', $clinic->id)
                ->exists();

            if ($emailExists) {
                return back()->withErrors([
                    'email' => trans('messages.auth.email_another_account')
                ]);
            }
        }
        }

        // =========================
        // ✅ CHECK PHONE
        // =========================
        if (!empty($request->phone)) {
        if ($phone !== preg_replace('/\D/', '', $clinic->phone)) {
            $phoneExists = Clinic::where('phone', $phone)
                ->where('id', '!=', $clinic->id)
                ->exists();

            if ($phoneExists) {
                return back()->withErrors([
                    'phone' => trans('messages.auth.phone_another_account')
                ]);
            }
        }
        }
        $data = $request->all();
        $clinic->update($data);
//        $edit_admin->name = $request->name;
//        $edit_admin->phone = $request->phone;
//        $edit_admin->email = $request->email;
//        $edit_admin->image = $request->image;
//        if ($request->password) {
//            $edit_admin->password = '';
//            $edit_admin->save();
//            if ($edit_admin) {
//                $edit_admin->password = Hash::make($request->password);
//                $edit_admin->save();
//            }
//        }
//        $edit_admin->save();
        session()->flash('success', trans('admin.updated'));
        return redirect()->back();
    }

    function add_password(ChangePasswordRequest $request)
    {
        $check_authorization = Clinic::where('id', Auth::user()->id)->first();
        if (isset($request->old_password)) {
            $check = Hash::check($request->old_password, $check_authorization->password);
            if (!$check) {
                session()->flash('failed', trans('messages.profile.wrong_old_password'));
                return redirect()->back();
            }
        }
        $data['password'] = Hash::make($request->new_password);

        if ($request->new_password) {
            $check_authorization->password = '';
            $check_authorization->save();
            if ($check_authorization) {
                $check_authorization->password = Hash::make($request->new_password);
                $check_authorization->save();
            }
        }
        $check_authorization->update($data);

        session()->flash('success', trans('messages.auth.forgetPassword'));
        return redirect()->back();
    }
}
