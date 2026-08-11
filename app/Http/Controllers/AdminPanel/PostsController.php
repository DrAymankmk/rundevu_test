<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AddTitle;
use App\Models\Clinic;
use App\Models\ClinicOffer;
use App\Models\ClinicPostsCount;
use App\Models\Posts;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class PostsController extends Controller
{
    // show posts
    function index()
    {
        $auth_app = Auth::user()->app_type == 7 ? Auth::user()->id : Auth::user()->parent_id;
        $clinics_doctors_count = Clinic::where('app_type', 3)->where('parent_id', $auth_app)->orderBy('id', 'desc')->count();
        $data['check_doctor_count'] = ClinicPostsCount::where('doctor_count_from', '<=', $clinics_doctors_count)->where('doctor_count_to', '>=', $clinics_doctors_count)->first();
        $data['posts'] = Posts::where('clinic_id', $auth_app)->orderBy('id', 'desc')->select('id', 'image', 'content','created_at')->paginate(10);
        return view('posts.index', compact('data'));
    }

    // add posts
    public function add_post(Request $request)
    {
        $auth_user = Auth::user()->app_type == 7 ? Auth::user()->id : Auth::user()->parent_id;
        $last_7_days = Posts::where('clinic_id',$auth_user)->where('created_at','>=', Carbon::now()->subdays(7))->count();
        if ($request->post_count == 0) {
            session()->flash('failed', trans('admin.no_post_doctor'));
            return redirect()->back();
        }
        if ($last_7_days >= $request->post_count) {
            session()->flash('failed', trans('admin.limit_posts'));
            return redirect()->back();
        }
        $data = $request->all();
        $data['clinic_id'] = $auth_user;
        $add_post = Posts::create($data);
        if ($add_post) {
            session()->flash('success', trans('messages.Added'));
            return redirect()->back();
        }
    }

    //Edit post
    public function edit_post($id, Request $request)
    {
        $edit_post = Posts::where('id', $id)->first();
        $data = $request->all();
        $edit_post->update($data);
        session()->flash('success', trans('messages.updated'));
        return redirect()->back();
    }

    // update status post
    public function update_status_post($id, $status)
    {
        $status_posts = Posts::where('id', $id)->first();
        $status_posts->status = $status;
        $status_posts->save();
        session()->flash('success', trans('messages.update_status'));
    }


    // delete post
    function destroy_post($id)
    {
        $post = Posts::where('id', $id)->first();
        $post->delete();
        session()->flash('success', trans('messages.deleted'));
        return redirect()->back();
    }
}
