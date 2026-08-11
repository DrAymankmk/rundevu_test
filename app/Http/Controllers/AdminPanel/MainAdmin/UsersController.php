<?php

namespace App\Http\Controllers\AdminPanel\MainAdmin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    // get users
    function index(Request $request)
    {
        $query = User::latest();
        if ($request->date_from && $request->date_to) {
            $query->whereBetween('created_at', [$request->date_from, $request->date_to]);
        }

        if ($request->q) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->q . '%')
                    ->orWhere('ID_Number', 'like', '%' . $request->q . '%')
                    ->orWhere('phone', 'like', '%' . $request->q . '%');
            });
        }
        $users = $query->paginate(20);
        $cities = City::latest()->get();
        return view('main_admin.users', compact('users','cities'));
    }

    function edit_user($id)
    {
        $user = User::findOrFail($id);
        $cities = City::where('status',1)->latest()->get();
        $nameWithoutHyphens = str_replace('-', ' ', $user->name); // Replace hyphens with spaces
        $nameParts = explode(' ', $nameWithoutHyphens); // Split the name into parts
        return view('main_admin.edit_user', compact('user','cities','nameParts'));
    }


    public function update_user($id, Request $request)
    {
        $edit_user = User::where('id', $id)->first();
        $data = $request->all();
        $data['name'] = $request->name . "-" . $request->father_name . "-" . $request->Grandfather_name . "-" . $request->family_name;
        $edit_user->update($data);
        session()->flash('success', trans('messages.updated'));
        return redirect()->back();
    }


    // delete user
    function destroy_user($id)
    {
        $user = User::where('id', $id)->first();
        $user->delete();
        session()->flash('success', trans('messages.deleted'));
        return redirect()->back();
    }

    function delete_user($id)
    {
        $user = User::find($id);
        if (!$user) {
                return response()->json(['status' => false, 'message' => 'المريض غير موجود'], 404);
            }
            // تنفيذ الحذف
        $user->delete();
            return response()->json(['status' => true, 'message' => trans('messages.deleted')]);
    }
}
