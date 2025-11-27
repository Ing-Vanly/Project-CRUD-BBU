<?php

namespace App\Http\Controllers;

use App\Support\PermissionMap;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $this->enforcePermission('role.view');

        $roles = Role::paginate(10);

        return view('admin.backends.role.index', compact('roles'));
    }

    public function create()
    {
        $this->enforcePermission('role.create');

        $permissionModules = PermissionMap::modules();

        return view('admin.backends.role.create', [
            'permissionModules' => $permissionModules,
            'selectedPermissions' => [],
        ]);
    }

    public function store(Request $request)
    {
        $this->enforcePermission('role.create');

        $availablePermissions = PermissionMap::allPermissions();

        $validated = $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => ['required', 'array'],
            'permissions.*' => ['string', Rule::in($availablePermissions)],
        ]);

        try {
            DB::beginTransaction();

            $role = Role::create(['name' => $validated['name']]);
            $role->syncPermissions($validated['permissions']);

            DB::commit();

            return redirect()
                ->route('role.index')
                ->with(['success' => true, 'msg' => __('Created Successfully!')]);
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with(['success' => false, 'msg' => __('Something went wrong!') . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $this->enforcePermission('role.edit');

        $role = Role::with('permissions')->findOrFail($id);

        return view('admin.backends.role.edit', [
            'role' => $role,
            'permissionModules' => PermissionMap::modules(),
            'selectedPermissions' => $role->permissions->pluck('name')->all(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->enforcePermission('role.edit');

        $availablePermissions = PermissionMap::allPermissions();

        $validated = $request->validate([
            'name' => 'required|unique:roles,name,' . $id,
            'permissions' => ['required', 'array'],
            'permissions.*' => ['string', Rule::in($availablePermissions)],
        ]);

        try {
            DB::beginTransaction();

            $role = Role::findOrFail($id);
            $role->update(['name' => $validated['name']]);
            $role->syncPermissions($validated['permissions']);

            DB::commit();

            return redirect()
                ->route('role.index')
                ->with(['success' => true, 'msg' => __('Updated Successfully!')]);
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with(['success' => false, 'msg' => __('Something went wrong!') . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $this->enforcePermission('role.delete');

        try {
            DB::beginTransaction();
            $role = Role::findOrFail($id);
            $role->delete();

            $roles = Role::latest('id')->paginate(10);
            $view = view('admin.backends.role.table', compact('roles'))->render();
            DB::commit();
            $output = [
                'status' => 1,
                'view' => $view,
                'msg' => __('Role Deleted successfully.'),
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $output = [
                'status' => 0,
                'msg' => __('Something went wrong'),
            ];
        }

        return response()->json($output);
    }
}
