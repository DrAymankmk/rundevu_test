<?php

namespace App\Http\Controllers\AdminPanel;

use App\Models\Admin;
use App\Models\AllPermissions;
use App\Models\Clinic;
use App\Models\ClinicsPermissions;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SuperVisorController extends Controller
{
    protected function availableSupervisorPermissions()
    {
        $authUser = Auth::user();
        $clinicAdminId = $authUser->id;
        $allowedIds = DB::table('model_has_permissions')
            ->where('model_type', Clinic::class)
            ->where('model_id', $clinicAdminId)
            ->pluck('permission_id')
            ->map(function ($id) {
                return (int) $id;
            });

        $restrictToCurrentAdmin = $allowedIds->isNotEmpty() && $authUser->app_type != 1;
        $allowedParentIds = Permission::whereIn('id', $allowedIds)
            ->where('parent_id', null)
            ->pluck('id')
            ->map(function ($id) {
                return (int) $id;
            });

        $query = Permission::with(['child' => function ($query) use ($allowedIds, $allowedParentIds, $restrictToCurrentAdmin) {
                if ($restrictToCurrentAdmin) {
                    $query->where(function ($query) use ($allowedIds, $allowedParentIds) {
                        $query->whereIn('id', $allowedIds)
                            ->orWhereIn('parent_id', $allowedParentIds);
                    });
                }
            }])
            ->where('parent_id', null)
            ->where('status', 1)
            ->where('type', 'clinic')
            ->orderBy('id');

        if ($authUser->app_type != 1) {
            $query->whereNotIn('permission', ['Branches', 'branches']);
        }

        if ($restrictToCurrentAdmin) {
            $query->where(function ($query) use ($allowedIds) {
                $query->whereIn('id', $allowedIds)
                    ->orWhereHas('child', function ($childQuery) use ($allowedIds) {
                        $childQuery->whereIn('id', $allowedIds);
                    });
            });
        }

        return $query->get()->filter(function ($permission) {
            return is_null($permission->parent_id) && ($permission->child->isNotEmpty() || $permission->flag == 1);
        });
    }

    protected function availableSupervisorPermissionIds()
    {
        return $this->availableSupervisorPermissions()
            ->flatMap(function ($permission) {
                return $permission->child->isNotEmpty()
                    ? $permission->child->pluck('id')
                    : collect([$permission->id]);
            })
            ->map(function ($id) {
                return (int) $id;
            })
            ->values()
            ->all();
    }

    // get all supervisor
    function index()
    {
        $auth_app = Auth::user()->id;
        $all_supervisor = Clinic::where('parent_id', $auth_app)->where('id', '!=', Auth::user()->id)->where('app_type', 6)->orderBy('id','desc')->get();
        return view('supervisor.index', compact('all_supervisor'));
    }

    // form add supervisor
    function create_supervisor(Request $request)
    {
        $permissions = $this->availableSupervisorPermissions();
        return view('supervisor.add', compact('permissions'));
    }

    // add supervisor in main admin
    function AddSupervisor(Request $request)
    {
        $auth_app = Auth::user()->id;

        $request->validate([
            'permissions' => 'required|array|min:1',
            'permissions.*' => ['integer', Rule::in($this->availableSupervisorPermissionIds())],
        ], [], [
            'permissions' => trans('admin.supervisor_permissions'),
        ]);
        $check_email = Clinic::where('phone', $request->phone)->orwhere('email', $request->email)->first();
        if ($check_email) {
            session()->flash('success', __(trans('admin.account_exists')));
            return redirect()->back();
        }
        $data = $request->all();
        $data['app_type'] = 6;
        $data['parent_id'] = $auth_app;
        $data['password'] = Hash::make($request->password);
        $data['jwt_token'] = Str::random(75);

        $add_account = Clinic::create($data);
        $add_account->syncPermissions($request->permissions);
        if ($add_account) {
//            if ($request->permissions) {
//                foreach ($request->permissions as $permission) {
//                    $get_parent = AllPermissions::where('id', $permission)->select('id', 'parent_id')->first();
//                    $add_permission = new ClinicsPermissions();
//                    $add_permission->child_id = $permission;
//                    $add_permission->admin_id = $add_account->id;
//                    $add_permission->parent_id = $get_parent->parent_id;
//                    $add_permission->save();
//                }
//            }
            session()->flash('success', __(trans('admin.add_supervisor_success')));
        } else {
            session()->flash('success', __(trans('messages.profile.missing_details')));
        }
        return redirect()->route('supervisor');

    }

    // form update supervisor
    function supervisor_update(Request $request, $id)
    {
        $permissions = $this->availableSupervisorPermissions();

        $supervisor = Clinic::with('supervisor_permission')->where('id', $id)->first();
        $supervisor->supervisor_permission->pluck('child_id')->toArray();
        return view('supervisor.edit', compact('permissions', 'supervisor'));
    }

    //Active or dis active account supervisor
    public function UpdateStatusSuper($id, $status)
    {
        $supervisor = Clinic::where('id', $id)->first();
        $supervisor->status = $status;
        $supervisor->save();
        session()->flash('success', __('تم تغيير حساب المشرف بنجاح'));
    }

    public function EditAccountSupervisor($id, Request $request)
    {
        $request->validate([
            'permissions' => 'required|array|min:1',
            'permissions.*' => ['integer', Rule::in($this->availableSupervisorPermissionIds())],
        ], [], [
            'permissions' => trans('admin.supervisor_permissions'),
        ]);

        $this->updateSuperVisor($id, $request->all());
        session()->flash('success', __('تم تعديل بيانات المشرف بنجاح'));
        return redirect()->route('supervisor');
    }

    public function updateSuperVisor($id, $input)
    {
        if (empty($input['permissions'])) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'permissions' => trans('validation.required', ['attribute' => trans('admin.supervisor_permissions')]),
            ]);
        }

        $supervisor = Clinic::whereId($id)->first();
        $supervisor->name = $input['name'];
        $supervisor->email = $input['email'];
        $supervisor->phone = $input['phone'];
        if (!empty($input['image'])) {
            $supervisor->image = $input['image'];
        }
        $supervisor->syncPermissions($input['permissions']);
//       return $supervisor->supervisor_permission()->sync($input['permissions']);

//        $check_already_permission = ClinicsPermissions::where('admin_id',$id)->get();
//        if ($check_already_permission) {
//            foreach ($check_already_permission as $per) {
//                if (!in_array($per->child_id, $input['permissions'])) {
//                    ClinicsPermissions::where('id',$per->id)->delete();
//                }
//            }
//        }
//
//
//        if ($input['permissions']) {
//            foreach ($input['permissions'] as $permission) {
//                $get_parent = AllPermissions::where('id', $permission)->select('id', 'parent_id')->first();
//
//             ClinicsPermissions::updateOrCreate([
//                    'admin_id' => $id,
//                    'child_id' => $permission,
//                ], [
//                    'parent_id' => $get_parent->parent_id,
//                    'admin_id' => $id,
//                    'child_id' => $permission,
//                ]);
//            }
//        }

        $supervisor->save();
        return $supervisor;
    }

    function destroyAccount($id)
    {
        Clinic::where('id', $id)->delete();
        session()->flash('success', __('تم حذف  حساب المشرف بنجاح'));
        return redirect()->back();
    }
}
