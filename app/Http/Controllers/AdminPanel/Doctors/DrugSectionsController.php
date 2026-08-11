<?php

namespace App\Http\Controllers\AdminPanel\Doctors;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Doctors\CreateUpdateDrugSectionRequest;
use App\Models\Drugs;

class DrugSectionsController extends Controller
{
    // show drug sections
    function index($drug_id)
    {
        $drug = Drugs::where('id', $drug_id)->first();
        return view('doctors.drugs.sections', compact('drug'));
    }

    // create drugs
    function create_drug_section($drug_id)
    {
        $drug = Drugs::where('id', $drug_id)->first();
        return view('doctors.drugs.add_sections',compact('drug'));
    }

    // create drugs
    function update_drug_section($id)
    {
        $section = Drugs::where('id',$id)->select('id','name_ar','name_en','status','name_'. $this->lang().' as title','medicine_type','concentration_ratio' ,'concentration_type')->first();
        return view('doctors.drugs.edit_sections',compact('section'));
    }

    // add drug_section
    public function add_drug_section($drug_id, CreateUpdateDrugSectionRequest $request)
    {
        $drug = Drugs::where('id', $drug_id)->first();
        $data = $request->all();
        $data['parent_id'] = $drug_id;
        $data['doctor_id'] = $drug->doctor_id;
        $add_section = Drugs::create($data);
        if ($add_section) {
            session()->flash('success', trans('messages.Added'));
            return redirect()->back();
        }
    }

    //Edit drug_section
    public function edit_drug_section($id, CreateUpdateDrugSectionRequest $request)
    {
        $edit_section = Drugs::where('id', $id)->first();
        $data = $request->all();
        $edit_section->update($data);
        session()->flash('success', trans('messages.updated'));
        return redirect()->back();
    }

    public function update_status_drug_section($id, $status)
    {
        $status_section = Drugs::where('id', $id)->first();
        $status_section->status = $status;
        $status_section->save();
        session()->flash('success', trans('admin.update_status'));
    }

    // delete drug_section
    function destroy_drug_section($id)
    {
        Drugs::where('id', $id)->delete();
        session()->flash('success', trans('messages.deleted'));
        return redirect()->back();
    }
}
