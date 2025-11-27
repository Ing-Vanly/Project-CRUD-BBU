<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $this->enforcePermission('user.view');

        $users = User::with('roles')->paginate(10);
        return view('admin.backends.user.index', compact('users'));
    }

    public function create()
    {
        $this->enforcePermission('user.create');

        $roles = Role::all();
        return view('admin.backends.user.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $this->enforcePermission('user.create');

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|exists:roles,name'
        ]);

        try {
            DB::beginTransaction();
            
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $user->assignRole($request->role);
            
            DB::commit();
            return redirect()->route('user.index')->with(['success' => true, 'msg' => __('User created successfully!')]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['success' => false, 'msg' => __('Something went wrong!') . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $this->enforcePermission('user.edit');

        $user = User::with('roles')->findOrFail($id);
        $roles = Role::all();
        return view('admin.backends.user.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $this->enforcePermission('user.edit');

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:8|confirmed',
            'role' => 'required|exists:roles,name'
        ]);

        try {
            DB::beginTransaction();
            
            $user = User::findOrFail($id);
            
            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
            ];
            
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }
            
            $user->update($updateData);
            $user->syncRoles([$request->role]);
            
            DB::commit();
            return redirect()->route('user.index')->with(['success' => true, 'msg' => __('User updated successfully!')]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['success' => false, 'msg' => __('Something went wrong!') . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $this->enforcePermission('user.delete');

        try {
            DB::beginTransaction();
            $user = User::findOrFail($id);
            $user->delete();

            $users = User::with('roles')->latest('id')->paginate(10);
            $view = view('admin.backends.user.table', compact('users'))->render();
            DB::commit();
            
            $output = [
                'status' => 1,
                'view' => $view,
                'msg' => __('User deleted successfully.')
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
