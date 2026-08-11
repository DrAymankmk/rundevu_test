<?php

namespace App\Http\Controllers\AdminPanel\MainAdmin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::latest()->get();
        return view('main_admin.roles_permissions.roles', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'status'  => 'required|in:1,0',
        ]);
        $data = $request->all();
        $add_role = Role::create($data);
        if ($add_role) {
            session()->flash('success', trans('messages.Added'));
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::whereId($id)->first();
        $permissions = Permission::withCount('child')->where(['parent_id'=>null,'type'=>'admin'])->where('status',1);
        return view('main_admin.roles_permissions.permissions', compact('role','permissions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'status'  => 'required|in:1,0',
        ]);

        $edit_role = Role::where('id', $id)->first();
        $data = $request->all();
        $edit_role->update($data);
        session()->flash('success', trans('messages.updated'));
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::where('id', $id)->first();
        $role->delete();
        session()->flash('success', trans('messages.deleted'));
        return redirect()->back();
    }

    function create_update_permissions($id,Request $request)
    {
        $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id', // Ensure each permission ID exists
        ]);
        // Find the role
        $role = Role::findOrFail($id);


        // Sync permissions (if provided)
        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions); // Sync selected permissions
        }

        session()->flash('success', __('تم تعديل البيانات بنجاح'));

        return redirect()->route('roles.index');
    }

    public function updatePermissions(Request $request, Role $role)
    {
        // لو معلمش على أي حاجة بيرجع array فاضي
        $permissions = $request->input('permissions', []);

        // syncPermissions يشيل القديم ويحط الجديد
        $role->syncPermissions($permissions);

        return redirect()
            ->back()
            ->with('success', __('admin.Permissions updated successfully'));
    }


}
