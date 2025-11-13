<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AuthorController extends Controller
{
    /**
     * Display a listing of the authors.
     */
    public function index(Request $request)
    {
        $query = Author::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('date_range')) {
            $dates = explode('|', $request->date_range);

            if (count($dates) === 2) {
                try {
                    $startDate = Carbon::parse(trim($dates[0]))->startOfDay();
                    $endDate = Carbon::parse(trim($dates[1]))->endOfDay();
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                } catch (\Throwable $th) {
                    // Ignore invalid date input and continue without filtering.
                }
            }
        }

        $authors = $query->latest('id')->paginate(10);

        if ($request->ajax()) {
            $view = view('admin.backends.author.table', compact('authors'))->render();
            return response()->json(['view' => $view]);
        }

        return view('admin.backends.author.index', compact('authors'));
    }

    /**
     * Show the form for creating a new author.
     */
    public function create()
    {
        return view('admin.backends.author.create');
    }

    /**
     * Store a newly created author in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:authors,email',
            'phone' => 'nullable|string|max:50',
            'website' => 'nullable|url|max:255',
        ]);

        try {
            DB::beginTransaction();
            Author::create($data);
            DB::commit();

            return redirect()->route('author.index')->with([
                'success' => true,
                'msg' => __('Author created successfully!')
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->back()->withInput()->with([
                'success' => false,
                'msg' => __('Something went wrong! Please try again.')
            ]);
        }
    }

    /**
     * Display the specified author.
     */
    public function show(Author $author)
    {
        return view('admin.backends.author.show', compact('author'));
    }

    /**
     * Show the form for editing the specified author.
     */
    public function edit(Author $author)
    {
        return view('admin.backends.author.edit', compact('author'));
    }

    /**
     * Update the specified author in storage.
     */
    public function update(Request $request, Author $author)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:authors,email,' . $author->id,
            'phone' => 'nullable|string|max:50',
            'website' => 'nullable|url|max:255',
        ]);

        try {
            DB::beginTransaction();
            $author->update($data);
            DB::commit();

            return redirect()->route('author.index')->with([
                'success' => true,
                'msg' => __('Author updated successfully!')
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->back()->withInput()->with([
                'success' => false,
                'msg' => __('Something went wrong! Please try again.')
            ]);
        }
    }

    /**
     * Remove the specified author from storage.
     */
    public function destroy(Request $request, Author $author)
    {
        try {
            DB::beginTransaction();
            $author->delete();
            DB::commit();

            if ($request->ajax()) {
                $authors = Author::latest('id')->paginate(10);
                $view = view('admin.backends.author.table', compact('authors'))->render();

                return response()->json([
                    'status' => 1,
                    'view' => $view,
                    'msg' => __('Author deleted successfully.')
                ]);
            }

            return redirect()->route('author.index')->with([
                'success' => true,
                'msg' => __('Author deleted successfully.')
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();

            if ($request->ajax()) {
                return response()->json([
                    'status' => 0,
                    'msg' => __('Something went wrong')
                ]);
            }

            return redirect()->back()->with([
                'success' => false,
                'msg' => __('Something went wrong! Please try again.')
            ]);
        }
    }
}
