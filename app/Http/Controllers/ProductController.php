<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Unit;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->enforcePermission('product.view');

        $query = Product::with(['category', 'brand', 'unit']);

        // Apply search filter
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Apply category filter
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Apply brand filter
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        // Apply price range filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Apply created date filter
        if ($request->filled('created')) {
            $query->whereDate('created_at', $request->created);
        }

        $products = $query->latest()->paginate(10);

        if ($request->ajax()) {
            $view = view('admin.backends.product.table', compact('products'))->render();
            return response()->json(['view' => $view]);
        }

        $categories = Category::all();
        $brands = Brand::all();

        return view('admin.backends.product.index', compact('products', 'categories', 'brands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->enforcePermission('product.create');

        $categories = Category::all();
        $brands = Brand::all();
        $units = Unit::all();

        return view('admin.backends.product.create', compact('categories', 'brands', 'units'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->enforcePermission('product.create');

        $request->validate([
            'name' => 'required|string|max:255|unique:products,name',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'unit_id' => 'nullable|exists:units,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->only(['name', 'description', 'price', 'stock', 'status', 'category_id', 'brand_id', 'unit_id']);

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/products'), $imageName);
            $data['image'] = 'uploads/products/' . $imageName;
        }

        Product::create($data);

        return redirect()->route('product.index')
            ->with('success', 1)
            ->with('msg', 'Product created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $this->enforcePermission('product.view');

        $product->load(['category', 'brand', 'unit']);
        return view('admin.backends.product.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $this->enforcePermission('product.edit');

        $categories = Category::all();
        $brands = Brand::all();
        $units = Unit::all();

        return view('admin.backends.product.edit', compact('product', 'categories', 'brands', 'units'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $this->enforcePermission('product.edit');

        $request->validate([
            'name' => 'required|string|max:255|unique:products,name,' . $product->id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'unit_id' => 'nullable|exists:units,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->only(['name', 'description', 'price', 'stock', 'status', 'category_id', 'brand_id', 'unit_id']);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image && file_exists(public_path($product->image))) {
                unlink(public_path($product->image));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/products'), $imageName);
            $data['image'] = 'uploads/products/' . $imageName;
        }

        $product->update($data);

        return redirect()->route('product.index')
            ->with('success', 1)
            ->with('msg', 'Product updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $this->enforcePermission('product.delete');

        // Delete image if exists
        if ($product->image && file_exists(public_path($product->image))) {
            unlink(public_path($product->image));
        }

        $product->delete();

        if (request()->ajax()) {
            $products = Product::with(['category', 'brand', 'unit'])->latest()->paginate(10);
            $view = view('admin.backends.product.table', compact('products'))->render();
            return response()->json([
                'status' => 1,
                'msg' => 'Product deleted successfully!',
                'view' => $view
            ]);
        }

        return redirect()->route('product.index')
            ->with('success', 1)
            ->with('msg', 'Product deleted successfully!');
    }
}
