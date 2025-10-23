<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Unit::query();

        // Search by name
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by created date
        if ($request->has('created') && $request->created !== '') {
            $query->whereDate('created_at', $request->created);
        }

        $units = $query->latest()->paginate(10);

        // Return only the table view for AJAX requests
        if ($request->ajax()) {
            $view = view('admin.backends.units.table', compact('units'))->render();
            return response()->json(['view' => $view]);
        }

        return view('admin.backends.units.index', compact('units'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $units = Unit::all();
        return view('admin.backends.units.create', compact('units'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'nullable|string|max:255|unique:units,slug',
            'description' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();
            $unit              = new Unit();
            $unit->name        = $request->name;
            $unit->slug        = $request->slug ?? Str::slug($request->name);
            $unit->description = $request->description;
            $unit->save();
            DB::commit();
            return redirect()->route('unit.index')->with(['success' => true, 'msg' => __('Unit Created Successfully!')]);
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
        $unit = Unit::findOrFail($id);
        return view('admin.backends.units.show', compact('unit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $unit = Unit::findOrFail($id);
        return view('admin.backends.units.edit', compact('unit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'nullable|string|max:200|unique:units,slug,' . $id,
            'description' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();
            $unit              = Unit::findOrFail($id);
            $unit->name        = $request->name;
            $unit->slug        = $request->slug ?? Str::slug($request->name);
            $unit->description = $request->description;
            $unit->save();
            DB::commit();
            return redirect()->route('unit.index')->with(['success' => true, 'msg' => __('Unit Updated Successfully!')]);
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
        try {
            DB::beginTransaction();
            $unit = Unit::findOrFail($id);
            $unit->delete();
            DB::commit();

            // For AJAX requests (from table)
            if ($request->ajax()) {
                $units = Unit::latest('id')->paginate(10);
                $view = view('admin.backends.units.table', compact('units'))->render();
                $output = [
                    'status' => 1,
                    'view' => $view,
                    'msg' => __('Unit Deleted successfully.')
                ];
                return response()->json($output);
            }

            // For regular form submit (from show page)
            return redirect()->route('unit.index')->with(['success' => true, 'msg' => __('Unit Deleted successfully.')]);
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
