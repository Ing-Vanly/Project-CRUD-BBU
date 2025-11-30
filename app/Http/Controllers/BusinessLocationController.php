<?php

namespace App\Http\Controllers;

use App\Models\BusinessLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class BusinessLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->enforcePermission('business_location.view');

        $query = BusinessLocation::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%')
                    ->orWhere('address', 'like', '%' . $search . '%')
                    ->orWhere('website', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('created')) {
            $query->whereDate('created_at', $request->created);
        }

        $locations = $query->latest()->paginate(10);

        if ($request->ajax()) {
            $view = view('admin.backends.business_locations.table', compact('locations'))->render();

            return response()->json(['view' => $view]);
        }

        return view('admin.backends.business_locations.index', compact('locations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->enforcePermission('business_location.create');

        return view('admin.backends.business_locations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->enforcePermission('business_location.create');

        $data = $this->validatedAttributes($request);

        try {
            DB::beginTransaction();

            $data = $this->attachUploads($request, $data);

            BusinessLocation::create($data);

            DB::commit();

            return redirect()->route('business-location.index')
                ->with('success', 1)
                ->with('msg', __('Business location created successfully!'));
        } catch (\Throwable $throwable) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('success', 0)
                ->with('msg', __('Unable to create business location. Please try again.'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(BusinessLocation $businessLocation)
    {
        $this->enforcePermission('business_location.view');

        return view('admin.backends.business_locations.show', compact('businessLocation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BusinessLocation $businessLocation)
    {
        $this->enforcePermission('business_location.edit');

        return view('admin.backends.business_locations.edit', compact('businessLocation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BusinessLocation $businessLocation)
    {
        $this->enforcePermission('business_location.edit');

        $data = $this->validatedAttributes($request);

        try {
            DB::beginTransaction();

            $data = $this->attachUploads($request, $data, $businessLocation);

            $businessLocation->update($data);

            DB::commit();

            return redirect()->route('business-location.index')
                ->with('success', 1)
                ->with('msg', __('Business location updated successfully!'));
        } catch (\Throwable $throwable) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('success', 0)
                ->with('msg', __('Unable to update business location. Please try again.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, BusinessLocation $businessLocation)
    {
        $this->enforcePermission('business_location.delete');

        try {
            DB::beginTransaction();

            $this->deleteFile($businessLocation->logo);
            $this->deleteFile($businessLocation->favicon);

            $businessLocation->delete();

            DB::commit();

            if ($request->ajax()) {
                $locations = BusinessLocation::latest()->paginate(10);
                $view = view('admin.backends.business_locations.table', compact('locations'))->render();

                return response()->json([
                    'status' => 1,
                    'msg' => __('Business location deleted successfully!'),
                    'view' => $view,
                ]);
            }

            return redirect()->route('business-location.index')
                ->with('success', 1)
                ->with('msg', __('Business location deleted successfully!'));
        } catch (\Throwable $throwable) {
            DB::rollBack();

            if ($request->ajax()) {
                return response()->json([
                    'status' => 0,
                    'msg' => __('Unable to delete business location. Please try again.'),
                ], 422);
            }

            return redirect()->back()
                ->with('success', 0)
                ->with('msg', __('Unable to delete business location. Please try again.'));
        }
    }

    /**
     * Validate request data.
     */
    private function validatedAttributes(Request $request): array
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'required|string',
            'website' => 'nullable|url|max:255',
            'footer_text' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,ico,webp|max:1024',
        ]);

        return Arr::except($validated, ['logo', 'favicon']);
    }

    /**
     * Merge upload paths into payload.
     */
    private function attachUploads(Request $request, array $data, ?BusinessLocation $location = null): array
    {
        foreach (['logo', 'favicon'] as $field) {
            $path = $this->storeImage($request, $field, $location?->{$field});

            if ($path) {
                $data[$field] = $path;
            } elseif ($location && $location->{$field}) {
                $data[$field] = $location->{$field};
            }
        }

        return $data;
    }

    /**
     * Store uploaded image or return null.
     */
    private function storeImage(Request $request, string $field, ?string $existingPath = null): ?string
    {
        if (!$request->hasFile($field)) {
            return null;
        }

        $file = $request->file($field);
        $destination = public_path('uploads/business_locations');

        if (!is_dir($destination)) {
            mkdir($destination, 0755, true);
        }

        if ($existingPath) {
            $this->deleteFile($existingPath);
        }

        $filename = uniqid($field . '_') . '.' . $file->getClientOriginalExtension();
        $file->move($destination, $filename);

        return 'uploads/business_locations/' . $filename;
    }

    /**
     * Delete file if present.
     */
    private function deleteFile(?string $path): void
    {
        if (!$path) {
            return;
        }

        $fullPath = public_path($path);

        if (file_exists($fullPath)) {
            @unlink($fullPath);
        }
    }
}
