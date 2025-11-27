<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->enforcePermission('category.view');

        $query = Category::query();

        // Search by name
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by created date
        if ($request->has('created') && $request->created !== '') {
            $query->whereDate('created_at', $request->created);
        }

        $categories = $query->latest()->paginate(10);

        // Return only the table view for AJAX requests
        if ($request->ajax()) {
            $view = view('admin.backends.categories.table', compact('categories'))->render();
            return response()->json(['view' => $view]);
        }

        return view('admin.backends.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->enforcePermission('category.create');

        $categories = Category::all();
        return view('admin.backends.categories.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->enforcePermission('category.create');

        $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'nullable|string|max:255|unique:categories,slug',
            'description' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();
            $category              = new Category();
            $category->name        = $request->name;
            $category->slug        = $request->slug ?? Str::slug($request->name);
            $category->description = $request->description;
            $category->save();
            DB::commit();
            return redirect()->route('category.index')->with(['success' => true, 'msg' => __('Category Created Successfully!')]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['success' => false, 'msg' => __('Something went wrong!') . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $this->enforcePermission('category.view');

        $category = Category::findOrFail($id);
        return view('admin.backends.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->enforcePermission('category.edit');

        $category = Category::findOrFail($id);
        return view('admin.backends.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->enforcePermission('category.edit');

        $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'nullable|string|max:200|unique:categories,slug,' . $id,
            'description' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();
            $category              = Category::findOrFail($id);
            $category->name        = $request->name;
            $category->slug        = $request->slug ?? Str::slug($request->name);
            $category->description = $request->description;
            $category->save();
            DB::commit();
            return redirect()->route('category.index')->with(['success' => true, 'msg' => __('Category Updated Successfully!')]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['success' => false, 'msg' => __('Something went wrong!') . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $this->enforcePermission('category.delete');

        try {
            DB::beginTransaction();
            $category = Category::findOrFail($id);
            $category->delete();
            DB::commit();

            // For AJAX requests (from table)
            if ($request->ajax()) {
                $categories = Category::latest('id')->paginate(10);
                $view = view('admin.backends.categories.table', compact('categories'))->render();
                $output = [
                    'status' => 1,
                    'view' => $view,
                    'msg' => __('Category Deleted successfully.')
                ];
                return response()->json($output);
            }

            // For regular form submit (from show page)
            return redirect()->route('category.index')->with(['success' => true, 'msg' => __('Category Deleted successfully.')]);
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax()) {
                return response()->json([
                    'status' => 0,
                    'msg' => __('Something went wrong')
                ]);
            }

            return redirect()->back()->with(['success' => false, 'msg' => __('Something went wrong!')]);
        }
    }
}
