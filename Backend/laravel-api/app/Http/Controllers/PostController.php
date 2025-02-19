<?php
namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        return Post::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
        ]);

        $post = Post::create($validated);

        return response()->json($post, 201);
    }

    public function show($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        return response()->json($post);
    }

    public function update(Request $request, $id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $validated = $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
        ]);

        $post->update($validated);

        return response()->json($post);
    }

    public function destroy($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $post->delete();

        return response()->json(['message' => 'Post deleted'], 204);
    }
}
