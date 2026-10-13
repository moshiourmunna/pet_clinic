<?php

namespace App\Http\Controllers;

use App\Models\Post;

class BlogController extends Controller
{
    public function index()
    {
        return view('blog.index', ['posts' => Post::published()->paginate(9)]);
    }

    public function show(Post $post)
    {
        abort_if(! $post->published_at || $post->published_at->isFuture(), 404);

        return view('blog.show', [
            'post' => $post,
            'related' => Post::published()->whereKeyNot($post->id)->take(3)->get(),
        ]);
    }
}
