<?php

namespace App\Http\Controllers\AdminPanel\MainAdmin;

use App\Http\Controllers\Controller;
use App\Models\ClinicSpecialist;
use App\Models\Specialty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class SpecialtiesControllerController extends Controller
{
    // get specialists
    function index()
    {
        $data['specializations'] = Specialty::withCount('sub_specialties_list','clinic_specialties')->where('parent_id',null)->orderBy('id', 'desc')->get();
        return view('main_admin.specialties', compact('data'));
    }

    // get sub specialist
    function sub_specialties($specialty_id)
    {
        $specialty = Specialty::with('sub_specialties_list')->whereId($specialty_id)->first();
        return view('main_admin.subSpecialties', compact('specialty'));
    }

    // add specialty
    public function add_specialty(Request $request)
    {
        $data = $this->validatedSpecialty($request);
        $data['created_by'] = Auth::user()->id;
        $data['image'] = $this->storeSpecialtyImage($request);

        $add_specialty = Specialty::create($data);
        if ($add_specialty) {
            session()->flash('success', trans('messages.Added'));
            return redirect()->back();
        }
    }

    //Edit specialty
    public function update_specialty($id, Request $request)
    {
        $edit_specialty = Specialty::where('id', $id)->first();
        abort_unless($edit_specialty, 404);

        $data = $this->validatedSpecialty($request);
        $data['created_by'] = Auth::user()->id;

        if ($request->hasFile('image')) {
            $this->deleteSpecialtyImage($edit_specialty);
            $data['image'] = $this->storeSpecialtyImage($request);
        } elseif ($request->boolean('remove_image')) {
            $this->deleteSpecialtyImage($edit_specialty);
            $data['image'] = null;
        }

        $edit_specialty->update($data);
        session()->flash('success', trans('messages.updated'));
        return redirect()->back();
    }
    // update status ClinicSpecialist
    public function update_status_specialty($id, $status)
    {
        $status = (int) $status;

        if (!in_array($status, [0, 1], true)) {
            return response()->json(['message' => trans('messages.something_went_wrong')], 422);
        }

        $specialty = Specialty::where('id', $id)->first();

        if (!$specialty) {
            return response()->json(['message' => trans('messages.something_went_wrong')], 404);
        }

        $specialty->status = $status;
        $specialty->created_by = Auth::user()->id;
        $specialty->save();

        session()->flash('success', trans('messages.update_status'));

        return response()->json([
            'message' => trans('messages.update_status'),
            'status' => $specialty->status,
        ]);
    }


    // delete ClinicSpecialist
    function destroy_specialty($id)
    {
        $specialty = Specialty::find($id);

        if (!$specialty) {
            return response()->json(['status' => false, 'message' => 'التخصص غير موجود'], 404);
        }

        // تنفيذ الحذف
          $specialty->delete();

        return response()->json(['status' => true, 'message' => trans('messages.deleted')]);
    }

    private function validatedSpecialty(Request $request): array
    {
        $data = $request->validate([
            'name_ar' => ['required', 'string', 'max:255'],
            'name_en' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:0,1'],
            'parent_id' => ['nullable', 'integer', 'exists:specialties,id'],
            'icon' => ['nullable', 'string', 'max:120', 'regex:/^(mdi mdi-|fa-solid fa-)[a-z0-9-]+$/'],
            'image' => ['nullable', 'image', 'max:4096'],
            'remove_image' => ['nullable', 'boolean'],
        ]);

        unset($data['image'], $data['remove_image']);

        if (! $request->exists('icon')) {
            unset($data['icon']);
        } else {
            $data['icon'] = filled($data['icon'] ?? null) ? $data['icon'] : null;
        }

        return $data;
    }

    private function storeSpecialtyImage(Request $request): ?string
    {
        if (! $request->hasFile('image')) {
            return null;
        }

        $file = $request->file('image');
        $name = time().rand(1111, 9999).'.'.$file->getClientOriginalExtension();
        $directory = public_path('media/specialties');

        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $file->move($directory, $name);

        return $name;
    }

    private function deleteSpecialtyImage(?Specialty $specialty): void
    {
        $file = $specialty?->getRawOriginal('image');
        if (! filled($file)) {
            return;
        }

        $path = public_path('media/specialties/'.$file);
        if (File::exists($path)) {
            File::delete($path);
        }
    }
}
