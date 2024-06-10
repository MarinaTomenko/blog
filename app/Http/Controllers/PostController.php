<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function search(Request $request) {
        $search = $request->input('search', '');
        
        $search = iconv_substr($search, 0, 64);
       
        $search = preg_replace('#[^0-9a-zA-ZА-Яа-яёЁ]#u', ' ', $search);
       
        $search = preg_replace('#\s+#u', ' ', $search);
        if (empty($search)) {
            return view('posts.search');
        }
        $posts = Post::select('posts.*', 'users.name as author')
            ->join('users', 'posts.author_id', '=', 'users.id')
            ->where('posts.title', 'like', '%'.$search.'%') // поиск по заголовку поста
            ->orWhere('posts.body', 'like', '%'.$search.'%') // поиск по тексту поста
            ->orWhere('users.name', 'like', '%'.$search.'%') // поиск по автору поста
            ->orderBy('posts.created_at', 'desc')
            ->paginate(4)
            ->appends(['search' => $request->input('search')]);;
        return view('posts.search', compact('posts'));
    }


    public function index()
    {
        $posts = Post::select('posts.*', 'users.name as author')
            ->join('users', 'posts.author_id', '=', 'users.id')
            ->orderBy('posts.created_at', 'desc')
            ->paginate(4);
        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
