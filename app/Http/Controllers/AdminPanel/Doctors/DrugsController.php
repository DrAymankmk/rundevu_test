<?php

namespace App\Http\Controllers\AdminPanel\Doctors;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AddDepartment;
use App\Models\AppType;
use App\Models\Drugs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DrugsController extends Controller
{
    // show drugs
    function index()
    {
        $data['drugs'] = Drugs::where(['doctor_id'=>Auth::user()->id,'parent_id'=>null])->orderBy('id', 'asc')->paginate(10);
        return view('doctors.drugs.index', compact('data'));
    }

    // create drugs
    function create_drug()
    {
        return view('doctors.drugs.add_drug');
    }

    // create drugs
    function update_drug($id)
    {
        $drug = Drugs::where('id',$id)->select('id','name_ar','name_en','status','name_'. $this->lang().' as title')->first();
        return view('doctors.drugs.edit_drug',compact('drug'));
    }

    // add drug
    public function add_drug(Request $request)
    {
        $auth_app =  Auth::user()->id ;
        $data = $request->all();
        $data['doctor_id'] = $auth_app;
        $add_drug = Drugs::create($data);
        if ($add_drug) {
            session()->flash('success', trans('messages.Added'));
            return redirect()->route('drugs');
        }
    }

    //Edit drug
    public function edit_drug($id, AddDepartment $request)
    {
        $edit_drug = Drugs::where('id', $id)->first();
        $data = $request->all();
        $edit_drug->update($data);
        session()->flash('success', trans('messages.updated'));
        return redirect()->route('drugs');
    }

    public function update_status_drug($id, $status)
    {
        $status_drug = Drugs::where('id', $id)->first();
        $status_drug->status = $status;
        $status_drug->save();
        session()->flash('success', trans('admin.change_status'));
    }


    // delete drug
    function destroy_drug($id)
    {
        $Drug = Drugs::where('id', $id)->first();
       $Drug->delete();
        session()->flash('success', trans('messages.deleted'));
        return redirect()->back();
    }
}
