<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::paginate(10);
        return view('admin.backends.role.index', compact('roles'));
    }

    public function create()
    {
        return view('admin.backends.role.create');
    }


    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:roles,name']);

        try {
            Role::create(['name' => $request->name]);
            return redirect()->route('role.index')->with(['success' => true, 'msg' => __('Created Successfully!')]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['success' => false, 'msg' =>  __('Something went wrong!') . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        return view('admin.backends.role.edit', compact('role'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required|unique:roles,name,' . $id]);

        try {
            $role = Role::findOrFail($id);
            $role->update(['name' => $request->name]);
            return redirect()->route('role.index')->with(['success' => true, 'msg' => __('Updated Successfully!')]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['success' => false, 'msg' =>  __('Something went wrong!') . $e->getMessage()]);
        }
    }


   public function destroy($id)
{
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
            'msg' => __('Role Deleted successfully.')
        ];
    } catch (Exception $e) {
        DB::rollBack();
        $output = [
            'status' => 0,
            'msg' => __('Something went wrong')
        ];
    }
    return response()->json($output);
}

}
