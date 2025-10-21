<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::latest()->get();
        return view('admin.backends.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.backends.categories.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

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
            return redirect()->route('categories.index')->with(['success' => true, 'msg' => __('Post Created Successfully!')]);
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
        return view('admin.backends.categories.show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('admin.backends.categories.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
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
            return redirect()->route('categories.index')->with(['success' => true, 'msg' => __('Category Updated Successfully!')]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['success' => false, 'msg' => __('Something went wrong!') . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();
            $category = Category::findOrFaill($id);
            $category->delete();

            $categorys = Category::with('user')->latest('id')->paginate(10);
            $view = view('admin.backends.categories.table', compact('categorys'))->render();
            DB::commit();
            $output = [
                'status' => 1,
                'view' => $view,
                'msg' => __('Category Deleted successfully.')
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            $output = [
                'status' => 0,
                'msg' => __('Something went wrong')
            ];
        }
        return response()->json($output);
    }
}
