<?php

namespace App\Http\Controllers;


use App\Post;



use App\Repositories\Posts;

use Carbon\Carbon;

class PostsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }
    public function index()
    {
        $posts = Post::latest()
            ->filter(request(['month', 'year']))
            ->get();

        return view('posts.index', compact('posts'));
    }

    public function show($id)
    {
        $post = Post::find($id);
        return view('posts.show', compact('post'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store()
    {

        $this->validate(request(), [
            'title' => 'required',
            "body" => 'required'
        ]);

        auth()->user()->publish(
            new Post(request(['title', 'body']))
        );

        session()->flash(

            'message', 'Your post has now been published.'
        );

        return redirect('/');

    }
}
