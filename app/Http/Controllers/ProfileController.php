<?php

namespace App\Http\Controllers;

use App\Traits\HandlesUserImages;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    use HandlesUserImages;

    public function edit(Request $request)
    {
        $user = $request->user();

        return view('admin.backends.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
                'image' => $this->handleImageUpload($request, $user->image),
            ];

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $user->update($updateData);

            DB::commit();

            return redirect()->route('profile.edit')->with([
                'success' => true,
                'msg' => __('Profile updated successfully!'),
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()->with([
                'success' => false,
                'msg' => __('Something went wrong!') . $e->getMessage(),
            ]);
        }
    }
}
