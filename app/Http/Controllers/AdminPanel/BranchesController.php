<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Repositories\App\MainRepository;
use App\Repositories\Auth\AuthRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BranchesController extends Controller
{

    public $repository;
    public $auth_repository;
    function __construct(Request $request, MainRepository $repository, AuthRepository $auth_repository)
    {
        $this->repository = $repository;
        $this->auth_repository = $auth_repository;
    }

    // show branch
    function index()
    {
        $auth_app = Auth::user()->app_type == 7 ? Auth::user()->id : Auth::user()->parent_id;

        $data['branches'] = Clinic::where('parent_id',$auth_app)->where('app_type', 7)->orderBy('id', 'desc')->select('id', 'name', 'email','image', 'phone', 'password', 'app_type','status','parent_id')->paginate(10);
        return view('branches.index', compact('data'));
    }

    // add branches
    public function add_branch(Request $request)
    {
        $auth_app = Auth::user()->app_type == 7 ? Auth::user()->id : Auth::user()->parent_id;

        if ($this->auth_repository->checkIfAccountExists($request->email)) {
            session()->flash('failed', trans('messages.auth.account_exists'));
            return redirect()->back();
        }
        if ($this->auth_repository->checkIfPhoneExists($request->phone)) {
            session()->flash('failed', trans('messages.auth.phone_exist'));
            return redirect()->back();
        }
        $data = $request->all();
        $data['parent_id'] = $auth_app;
        $data['app_type'] = 7;
        $data['password'] = Hash::make($request->password);
        $data['jwt_token'] = Str::random(75);
        $add_branch = Clinic::create($data);
        if ($add_branch) {
            session()->flash('success', trans('messages.Added'));
            return redirect()->back();
        }
    }

    //Edit post
    public function edit_branch($id, Request $request)
    {
        $check_email =  Clinic::where('email', $request->email)->where('id','!=',$id)->first();
        $check_phone =  Clinic::where('phone', $request->phone)->where('id','!=',$id)->first();

        if ($check_email) {
            session()->flash('failed', trans('messages.auth.account_exists'));
            return redirect()->back();
        }
        if ($check_phone) {
            session()->flash('failed', trans('messages.auth.phone_exist'));
            return redirect()->back();
        }
        $edit_branch = Clinic::where('id', $id)->first();
        $edit_branch->name = $request->name;
        $edit_branch->phone = $request->phone;
        $edit_branch->email = $request->email;
        $edit_branch->image = $request->image;
        $data = $request->all();
        if (!empty($request->password)) {
            $edit_branch->password = '';
            $edit_branch->save();
            if ($edit_branch) {
                $edit_branch->password = Hash::make($request->password);
                $edit_branch->save();
            }
        }
        $edit_branch->save();
//        $edit_branch->update($data);
        session()->flash('success', trans('messages.updated'));
        return redirect()->back();
    }

    // update status branch
    public function update_status_branch($id, $status)
    {
        $status_branch = Clinic::where('id', $id)->first();
        $status_branch->status = $status;
        $status_branch->save();
        session()->flash('success', trans('messages.update_status'));
    }


    // delete branch
    function destroy_branch($id)
    {
        $branch = Clinic::where('id', $id)->first();
        $branch->delete();
        session()->flash('success', trans('messages.deleted'));
        return redirect()->back();
    }
}
