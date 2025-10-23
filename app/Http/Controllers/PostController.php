<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with('user');

        // Search by title
        if ($request->filled('search')) {
            $query->where('title', 'LIKE', '%' . $request->search . '%');
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        // Filter by published
        if ($request->filled('published')) {
            $query->where('is_published', $request->published);
        }

        $posts = $query->latest('id')->paginate(10);

        // For AJAX requests, return only the table
        if ($request->ajax()) {
            $view = view('admin.backends.post.table', compact('posts'))->render();
            return response()->json(['view' => $view]);
        }

        return view('admin.backends.post.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.backends.post.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:200',
            'content' => 'required|string',
            'slug' => 'nullable|string|max:200|unique:posts,slug',
            'is_active' => 'nullable|boolean',
            'is_published' => 'nullable|boolean',
        ]);

        try {
            DB::beginTransaction();

            $post = new Post();
            $post->title = $request->title;
            $post->content = $request->content;
            $post->slug = $request->slug ?: Str::slug($request->title);
            $post->is_active = $request->has('is_active') ? 1 : 0;
            $post->is_published = $request->has('is_published') ? 1 : 0;
            $post->user_id = auth()->id();
            $post->save();

            DB::commit();
            return redirect()->route('post.index')->with(['success' => true, 'msg' => __('Post Created Successfully!')]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['success' => false, 'msg' => __('Something went wrong!') . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $post = Post::with('user')->findOrFail($id);
        return view('admin.backends.post.show', compact('post'));
    }

    public function edit($id)
    {
        $post = Post::findOrFail($id);
        return view('admin.backends.post.edit', compact('post'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:200',
            'content' => 'required|string',
            'slug' => 'nullable|string|max:200|unique:posts,slug,' . $id,
            'is_active' => 'nullable|boolean',
            'is_published' => 'nullable|boolean',
        ]);

        try {
            DB::beginTransaction();

            $post = Post::findOrFail($id);
            $post->title = $request->title;
            $post->content = $request->content;
            $post->slug = $request->slug ?: Str::slug($request->title);
            $post->is_active = $request->has('is_active') ? 1 : 0;
            $post->is_published = $request->has('is_published') ? 1 : 0;
            $post->save();

            DB::commit();
            return redirect()->route('post.index')->with(['success' => true, 'msg' => __('Post Updated Successfully!')]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['success' => false, 'msg' => __('Something went wrong!') . $e->getMessage()]);
        }
    }

    public function destroy(Request $request, string $id)
    {
        try {
            DB::beginTransaction();
            $post = Post::findOrFail($id);
            $post->delete();
            DB::commit();

            // For AJAX requests (from table)
            if ($request->ajax()) {
                $posts = Post::with('user')->latest('id')->paginate(10);
                $view = view('admin.backends.post.table', compact('posts'))->render();
                $output = [
                    'status' => 1,
                    'view' => $view,
                    'msg' => __('Post Deleted successfully.')
                ];
                return response()->json($output);
            }

            // For regular form submit (from show page)
            return redirect()->route('post.index')->with([
                'success' => true,
                'msg' => __('Post Deleted successfully.')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax()) {
                return response()->json([
                    'status' => 0,
                    'msg' => __('Something went wrong')
                ]);
            }

            return redirect()->back()->with([
                'success' => false,
                'msg' => __('Something went wrong!')
            ]);
        }
    }


    public function togglePublished($id)
    {
        try {
            DB::beginTransaction();
            $post = Post::findOrFail($id);
            $post->is_published = !$post->is_published;
            $post->save();

            DB::commit();
            $output = [
                'status' => 1,
                'is_published' => $post->is_published,
                'msg' => $post->is_published ? __('Post published successfully.') : __('Post unpublished successfully.')
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
