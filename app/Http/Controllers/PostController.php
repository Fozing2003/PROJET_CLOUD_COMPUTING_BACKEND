<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index() {
        return Post::with('comments', 'user')->latest()->get();
    }

    public function show(Post $post) {
        return $post->load('comments', 'user');
    }

   public function store(Request $request) {
    $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'media' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi|max:4096', // 4MB max
    ]);

    $mediaPath = null;
    if ($request->hasFile('media')) {
        $mediaPath = $request->file('media')->store('posts', 'public'); // stockage dans storage/app/public/posts
    }

    $post = Post::create([
        'user_id' => Auth::id(),
        'title' => $request->title,
        'content' => $request->content,
        'media' => $mediaPath,
    ]);

    return response()->json($post, 201);
}


    public function update(Request $request, Post $post) {
        if ($post->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'media' => 'nullable|string',
        ]);

        $post->update($request->all());
        return response()->json($post);
    }

    public function destroy(Post $post) {
        if ($post->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $post->delete();
        return response()->json(['message' => 'Post deleted']);
    }
}

