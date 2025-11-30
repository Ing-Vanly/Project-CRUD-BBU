<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->enforcePermission('brand.view');

        $query = Brand::query();

        // Apply search filter
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Apply created date filter
        if ($request->filled('created')) {
            $query->whereDate('created_at', $request->created);
        }

        $brands = $query->latest()->paginate(10);

        if ($request->ajax()) {
            $view = view('admin.backends.brand.table', compact('brands'))->render();
            return response()->json(['view' => $view]);
        }

        return view('admin.backends.brand.index', compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->enforcePermission('brand.create');

        return view('admin.backends.brand.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->enforcePermission('brand.create');

        $request->validate([
            'name' => 'required|string|max:255|unique:brands,name',
            'slug' => 'nullable|string|max:255|unique:brands,slug',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->only(['name', 'description']);

        // Generate slug if not provided
        $data['slug'] = $request->slug ?: Str::slug($request->name);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoName = time() . '_' . $logo->getClientOriginalName();
            $logo->move(public_path('uploads/brands'), $logoName);
            $data['logo'] = 'uploads/brands/' . $logoName;
        }

        Brand::create($data);

        return redirect()->route('brand.index')
            ->with('success', 1)
            ->with('msg', __('Brand created successfully!'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {
        $this->enforcePermission('brand.view');

        return view('admin.backends.brand.show', compact('brand'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Brand $brand)
    {
        $this->enforcePermission('brand.edit');

        return view('admin.backends.brand.edit', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Brand $brand)
    {
        $this->enforcePermission('brand.edit');

        $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,' . $brand->id,
            'slug' => 'nullable|string|max:255|unique:brands,slug,' . $brand->id,
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->only(['name', 'description']);

        // Generate slug if not provided
        $data['slug'] = $request->slug ?: Str::slug($request->name);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($brand->logo && file_exists(public_path($brand->logo))) {
                unlink(public_path($brand->logo));
            }

            $logo = $request->file('logo');
            $logoName = time() . '_' . $logo->getClientOriginalName();
            $logo->move(public_path('uploads/brands'), $logoName);
            $data['logo'] = 'uploads/brands/' . $logoName;
        }

        $brand->update($data);

        return redirect()->route('brand.index')
            ->with('success', 1)
            ->with('msg', __('Brand updated successfully!'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        $this->enforcePermission('brand.delete');

        // Delete logo if exists
        if ($brand->logo && file_exists(public_path($brand->logo))) {
            unlink(public_path($brand->logo));
        }

        $brand->delete();

        if (request()->ajax()) {
            $brands = Brand::latest()->paginate(10);
            $view = view('admin.backends.brand.table', compact('brands'))->render();
            return response()->json([
                'status' => 1,
                'msg' => __('Brand deleted successfully!'),
                'view' => $view
            ]);
        }

        return redirect()->route('brand.index')
            ->with('success', 1)
            ->with('msg', __('Brand deleted successfully!'));
    }
}
